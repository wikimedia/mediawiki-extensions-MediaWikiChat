<?php

class ChatParser extends Parser {
	
	public function parseLight( $text, Title $title, ParserOptions $options, $linestart = true, $clearState = true, $revid = null ) {
		/**
		 * First pass--just handle <nowiki> sections, pass the rest off
		 * to internalParse() which does all the real work.
		 */

		global $wgUseTidy, $wgAlwaysUseTidy;
		$fname = __METHOD__.'-' . wfGetCaller();
		wfProfileIn( __METHOD__ );
		wfProfileIn( $fname );

		$this->startParse( $title, $options, self::OT_HTML, $clearState );

		# Remove the strip marker tag prefix from the input, if present.
		if ( $clearState ) {
			$text = str_replace( $this->mUniqPrefix, '', $text );
		}

		$oldRevisionId = $this->mRevisionId;
		$oldRevisionObject = $this->mRevisionObject;
		$oldRevisionTimestamp = $this->mRevisionTimestamp;
		$oldRevisionUser = $this->mRevisionUser;
		if ( $revid !== null ) {
			$this->mRevisionId = $revid;
			$this->mRevisionObject = null;
			$this->mRevisionTimestamp = null;
			$this->mRevisionUser = null;
		}

		$text = $this->internalParseLight( $text );

		$text = $this->mStripState->unstripGeneral( $text );

		# Clean up special characters, only run once, next-to-last before doBlockLevels
		$fixtags = array(
		# french spaces, last one Guillemet-left
		# only if there is something before the space
		'/(.) (?=\\?|:|;|!|%|\\302\\273)/' => '\\1&#160;',
		# french spaces, Guillemet-right
		'/(\\302\\253) /' => '\\1&#160;',
		'/&#160;(!\s*important)/' => ' \\1', # Beware of CSS magic word !important, bug #11874.
		);
		$text = preg_replace( array_keys( $fixtags ), array_values( $fixtags ), $text );

		//$text = $this->doBlockLevels( $text, $linestart );

		$this->replaceLinkHolders( $text );

		/**
		 * The input doesn't get language converted if
		 * a) It's disabled
		 * b) Content isn't converted
		 * c) It's a conversion table
		 * d) it is an interface message (which is in the user language)
		*/
		if ( !( $options->getDisableContentConversion()
				|| isset( $this->mDoubleUnderscores['nocontentconvert'] ) ) )
		{
			# Run convert unconditionally in 1.18-compatible mode
			global $wgBug34832TransitionalRollback;
			if ( $wgBug34832TransitionalRollback || !$this->mOptions->getInterfaceMessage() ) {
				# The position of the convert() call should not be changed. it
				# assumes that the links are all replaced and the only thing left
				# is the <nowiki> mark.
				$text = $this->getConverterLanguage()->convert( $text );
			}
		}

		/**
			* A converted title will be provided in the output object if title and
			* content conversion are enabled, the article text does not contain
			* a conversion-suppressing double-underscore tag, and no
			* {{DISPLAYTITLE:...}} is present. DISPLAYTITLE takes precedence over
			* automatic link conversion.
			*/
		if ( !( $options->getDisableTitleConversion()
				|| isset( $this->mDoubleUnderscores['nocontentconvert'] )
				|| isset( $this->mDoubleUnderscores['notitleconvert'] )
				|| $this->mOutput->getDisplayTitle() !== false ) )
		{
			$convruletitle = $this->getConverterLanguage()->getConvRuleTitle();
			if ( $convruletitle ) {
				$this->mOutput->setTitleText( $convruletitle );
			} else {
				$titleText = $this->getConverterLanguage()->convertTitle( $title );
				$this->mOutput->setTitleText( $titleText );
			}
		}

		$text = $this->mStripState->unstripNoWiki( $text );

		wfRunHooks( 'ParserBeforeTidy', array( &$this, &$text ) );

		$text = $this->replaceTransparentTags( $text );
		$text = $this->mStripState->unstripGeneral( $text );

		$text = Sanitizer::normalizeCharReferences( $text );

		if ( ( $wgUseTidy && $this->mOptions->getTidy() ) || $wgAlwaysUseTidy ) {
			$text = MWTidy::tidy( $text );
		} else {
			# attempt to sanitize at least some nesting problems
			# (bug #2702 and quite a few others)
			$tidyregs = array(
			# ''Something [http://www.cool.com cool''] -->
			# <i>Something</i><a href="http://www.cool.com"..><i>cool></i></a>
			'/(<([bi])>)(<([bi])>)?([^<]*)(<\/?a[^<]*>)([^<]*)(<\/\\4>)?(<\/\\2>)/' =>
			'\\1\\3\\5\\8\\9\\6\\1\\3\\7\\8\\9',
			# fix up an anchor inside another anchor, only
			# at least for a single single nested link (bug 3695)
			'/(<a[^>]+>)([^<]*)(<a[^>]+>[^<]*)<\/a>(.*)<\/a>/' =>
			'\\1\\2</a>\\3</a>\\1\\4</a>',
			# fix div inside inline elements- doBlockLevels won't wrap a line which
			# contains a div, so fix it up here; replace
			# div with escaped text
			'/(<([aib]) [^>]+>)([^<]*)(<div([^>]*)>)(.*)(<\/div>)([^<]*)(<\/\\2>)/' =>
			'\\1\\3&lt;div\\5&gt;\\6&lt;/div&gt;\\8\\9',
			# remove empty italic or bold tag pairs, some
			# introduced by rules above
			'/<([bi])><\/\\1>/' => '',
			);

			$text = preg_replace(
					array_keys( $tidyregs ),
					array_values( $tidyregs ),
					$text );
		}

		if ( $this->mExpensiveFunctionCount > $this->mOptions->getExpensiveParserFunctionLimit() ) {
			$this->limitationWarn( 'expensive-parserfunction',
					$this->mExpensiveFunctionCount,
					$this->mOptions->getExpensiveParserFunctionLimit()
			);
		}

		wfRunHooks( 'ParserAfterTidy', array( &$this, &$text ) );

		# Information on include size limits, for the benefit of users who try to skirt them
		if ( $this->mOptions->getEnableLimitReport() ) {
			$max = $this->mOptions->getMaxIncludeSize();
			$PFreport = "Expensive parser function count: {$this->mExpensiveFunctionCount}/{$this->mOptions->getExpensiveParserFunctionLimit()}\n";
			$limitReport =
			"NewPP limit report\n" .
			"Preprocessor visited node count: {$this->mPPNodeCount}/{$this->mOptions->getMaxPPNodeCount()}\n" .
			"Preprocessor generated node count: " .
			"{$this->mGeneratedPPNodeCount}/{$this->mOptions->getMaxGeneratedPPNodeCount()}\n" .
			"Post-expand include size: {$this->mIncludeSizes['post-expand']}/$max bytes\n" .
			"Template argument size: {$this->mIncludeSizes['arg']}/$max bytes\n".
			"Highest expansion depth: {$this->mHighestExpansionDepth}/{$this->mOptions->getMaxPPExpandDepth()}\n".
			$PFreport;
			wfRunHooks( 'ParserLimitReport', array( $this, &$limitReport ) );
			$text .= "\n<!-- \n$limitReport-->\n";
		}
		$this->mOutput->setText( $text );

		$this->mRevisionId = $oldRevisionId;
		$this->mRevisionObject = $oldRevisionObject;
		$this->mRevisionTimestamp = $oldRevisionTimestamp;
		$this->mRevisionUser = $oldRevisionUser;
		wfProfileOut( $fname );
		wfProfileOut( __METHOD__ );

		return $this->mOutput;
	}

	function internalParseLight( $text, $isMain = true, $frame = false ) {
		wfProfileIn( __METHOD__ );

		$origText = $text;

		# Hook to suspend the parser in this state
		if ( !wfRunHooks( 'ParserBeforeInternalParse', array( &$this, &$text, &$this->mStripState ) ) ) {
			wfProfileOut( __METHOD__ );
			return $text ;
		}

		# if $frame is provided, then use $frame for replacing any variables
		if ( $frame ) {
			# use frame depth to infer how include/noinclude tags should be handled
			# depth=0 means this is the top-level document; otherwise it's an included document
			if ( !$frame->depth ) {
				$flag = 0;
			} else {
				$flag = Parser::PTD_FOR_INCLUSION;
			}
			$dom = $this->preprocessToDom( $text, $flag );
			$text = $frame->expand( $dom );
		} else {
			# if $frame is not provided, then use old-style replaceVariables
			$text = $this->replaceVariables( $text );
		}

		wfRunHooks( 'InternalParseBeforeSanitize', array( &$this, &$text, &$this->mStripState ) );
		$text = Sanitizer::removeHTMLtags( $text, array( &$this, 'attributeStripCallback' ), false, array_keys( $this->mTransparentTagHooks ) );
		wfRunHooks( 'InternalParseBeforeLinks', array( &$this, &$text, &$this->mStripState ) );

		# Tables need to come after variable replacement for things to work
		# properly; putting them before other transformations should keep
		# exciting things like link expansions from showing up in surprising
		# places.
		//$text = $this->doTableStuff( $text );

		$text = preg_replace( '/(^|\n)-----*/', '\\1<hr />', $text );

		//$text = $this->doDoubleUnderscore( $text );

		//$text = $this->doHeadings( $text );
		if ( $this->mOptions->getUseDynamicDates() ) {
			$df = DateFormatter::getInstance();
			$text = $df->reformat( $this->mOptions->getDateFormat(), $text );
		}
		$text = $this->replaceInternalLinks( $text );
		$text = $this->doAllQuotes( $text );
		$text = $this->replaceExternalLinks( $text );

		# replaceInternalLinks may sometimes leave behind
		# absolute URLs, which have to be masked to hide them from replaceExternalLinks
		$text = str_replace( $this->mUniqPrefix.'NOPARSE', '', $text );

		$text = $this->doMagicLinks( $text );
		//$text = $this->formatHeadings( $text, $origText, $isMain );

		wfProfileOut( __METHOD__ );
		return $text;
	}
}