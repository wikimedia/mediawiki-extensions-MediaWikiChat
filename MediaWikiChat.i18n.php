<?php
/**
 * Internationalisation file for the MediaWikiChat extension.
 *
 * @file
 * @ingroup Extensions
 */
$messages = array();

/** English
 * @author Adam Carter
 */
$messages['en'] = array(
	'chat' => 'Chat',
	'chat-desc' => 'Provides a lightweight chat client and server',

	'chat-type-your-message' => 'Type your message',
	'chat-type-your-private-message' => 'Type your private message',
	'chat-no-other-users' => 'No other users on chat',
	'chat-blocked-from-chat' => 'You have been blocked from this chat.',
	'chat-not-allowed' => 'You are not allowed to chat, try logging in first.',
	'chat-sounds' => 'Play sounds',

	'chat-flood' => 'You can\'t send messages that fast, please wait before sending more.',
	'chat-too-long' => 'That message was too long, please send a shorter message.',

	'chat-just-now' => 'just now',
	'chat-a-minute-ago' => 'a minute ago',
	'chat-yesterday' => 'yesterday',

	'chat-kick' => 'kick',
	'chat-block' => 'block',

	'chat-youve-been-kicked' => 'You have been {{GENDER:$2|kicked}} by $1. Refresh the page to chat.',
	'chat-you-kicked' => 'You {{GENDER:$2|kicked}} $1.',
	'chat-kicked' => '$1 {{GENDER:$3|kicked}} $2.',
	'chat-youve-been-blocked' => 'You have been {{GENDER:$2|blocked}} by $1.',
	'chat-you-blocked' => 'You {{GENDER:$2|blocked}} $1.',
	'chat-blocked' => '$1 {{GENDER:$3|blocked}} $2.',
	'chat-you-unblocked' => 'You {{GENDER:$2|unblocked}} $1.',
	'chat-unblocked' => '$1 {{GENDER:$3|unblocked}} $2.',
	'chat-joined' => '$1 has {{GENDER:$2|joined}} the chat.',
	'chat-left' => '$1 has {{GENDER:$2|left}} chat.',

	'chat-private-message' => 'click to private message',
	'chat-user-is-moderator' => 'This user {{GENDER:$1|is}} a moderator',
	'chat-you-are-moderator' => 'You {{GENDER:$1|are}} a moderator',

	'chat-topic' => 'Welcome to {{SITENAME}} chat. ([[Special:Log/chat|chat log]])',
	'chat-change-preferences' => 'Change your chat preferences (fullscreen and sounds)',

	'chat-sidebar-online' => 'Online users in chat:',
	'chat-sidebar-join' => 'Join the chat',

	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png', // Ignore for translation
	'chat-smileys' => '', // Ignore for translation

	'log-name-chat' => 'Chat log',
	'log-description-chat' => 'Messages sent by MediaWikiChat, as well as user kicks',
	'logentry-chat-send' => '$1: $4', // Optional for translation
	'logentry-chat-kick' => '$1 {{GENDER:$1|kicked}} $4',
	'log-show-hide-chat' => '$1 chat log',

	'log-name-privatechat' => 'Private chat log',
	'log-description-privatechat' => 'Private messages sent by MediaWikiChat',
	'logentry-privatechat-send' => '$1 to $5: $4', // Optional for translation
	'log-show-hide-chat' => '$1 private chat log',

	'group-chatmod' => 'Chat moderators',
	'group-chatmod-member' => '{{GENDER:$1|chat moderator}}',
	'grouppage-chatmod' => '{{ns:project}}:Chat moderators',

	'group-blockedfromchat' => 'Users blocked from chat',
	'group-blockedfromchat-member' => '{{GENDER:$1|blocked from chat}}',
	'grouppage-blockedfromchat' => '{{ns:project}}:Users blocked from chat',

	'right-mediawikichat-chat' => 'Use Special:Chat',
	'right-mediawikichat-modchat' => 'Block and kick (if enabled) users from Special:Chat',

	'tog-chat-fullscreen' => 'Use chat in fullscreen mode (experimental)',
	'tog-chat-ping-mention' => 'Play a sound on receiving mentions',
	'tog-chat-ping-pm' => 'Play a sound on receiving private messages',
	'tog-chat-ping-message' => 'Play a sound on receiving normal messages',
	'prefs-chat' => 'Chat'
);

/** Message documentation (Message documentation)
 * @author Adam Carter
 * @author Liuxinyu970226
 * @author Shirayuki
 */
$messages['qqq'] = array(
	'chat' => '{{doc-special|Chat}}
{{Identical|Chat}}',
	'chat-desc' => '{{desc}}',
	'chat-type-your-message' => 'Used as placeholder shown in the input field you type your message in.',
	'chat-type-your-private-message' => 'The same as chat-type-your-message, but for private messages, shown in the input field for typing private messages',
	'chat-no-other-users' => 'Shown in the user list when there are no other users on chat',
	'chat-blocked-from-chat' => 'Shown to users who have been blocked from chat',
	'chat-not-allowed' => 'Shown to users who do not have sufficient permissions to chat (normally users who are not logged in)',
	'chat-sounds' => 'Unused at this time.',
	'chat-too-long' => 'Shown to users when their message was too long to send.',
	'chat-just-now' => 'Timestamps: shown when a message was sent in the last 30 seconds.
{{Related|Chat-ago}}',
	'chat-yesterday' => 'Timestamps: the message was sent yesterday.
{{Related|Chat-ago}}
{{Identical|Yesterday}}',
	'chat-block' => 'The link shown to chatmods to block a user.
{{Identical|Block}}',
	'chat-youve-been-kicked' => 'Shown to users who have been kicked from the chat. Parameters:
* $1 - the user who kicked from the chat
* $2 - GENDER of the user who kicked
See also:
* {{msg-mw|Chat-youve-been-blocked}}',
	'chat-you-kicked' => 'Shown when the current user kicked the user $1. Parameters:
* $1 - username
* $2 - GENDER
See also:
* {{msg-mw|Chat-you-blocked}}
* {{msg-mw|Chat-you-unblocked}}',
	'chat-kicked' => 'Shown when the user $1 kicked the user $2. Parameters:
* $1 - the user who kicked
* $2 - the user who was kicked
* $3 - GENDER of the user who kicked
See also:
* {{msg-mw|Chat-blocked}}
* {{msg-mw|Chat-unblocked}}',
	'chat-youve-been-blocked' => 'Shown when the current user has been blocked, by the user $1. Parameters:
* $1 - the user who has blocked
* $2 - GENDER of the user who has blocked
See also:
* {{msg-mw|Chat-youve-been-kicked}}',
	'chat-you-blocked' => 'Shown when the current user blocked the user $1. Parameters:
* $1 - username
* $2 - GENDER
See also:
* {{msg-mw|Chat-you-kicked}}
* {{msg-mw|Chat-you-unblocked}}',
	'chat-blocked' => 'Shown when the user $1 blocked the user $2. Parameters:
* $1 - the user who blocked
* $2 - the user who was blocked
* $3 - GENDER of the user who blocked
See also:
* {{msg-mw|Chat-kicked}}
* {{msg-mw|Chat-unblocked}}',
	'chat-you-unblocked' => 'Shown when the current user unblocked the user $1. Parameters:
* $1 - username
* $2 - GENDER
See also:
* {{msg-mw|Chat-you-kicked}}
* {{msg-mw|Chat-you-blocked}}',
	'chat-unblocked' => 'Shown when the user $1 unblocked the user $2. Parameters:
* $1 - the user who unblocked
* $2 - the user who was unblocked
* $3 - GENDER of the user who unblocked
See also:
* {{msg-mw|Chat-kicked}}
* {{msg-mw|Chat-blocked}}',
	'chat-joined' => 'Shown when the user $1 joined the chat. Parameters:
* $1 - the username
* $2 - GENDER of the user
See also:
* {{msg-mw|Chat-left}}',
	'chat-left' => 'Shown when the user $1 left the chat. Parameters:
* $1 - the username
* $2 - GENDER of the user
See also:
* {{msg-mw|Chat-joined}}',
	'chat-private-message' => 'The link shown to users to private message another user.
{{Identical|Private message}}',
	'chat-user-is-moderator' => 'Parameters:
* $1 - GENDER of the user
See also:
* {{msg-mw|Chat-you-are-moderator}}',
	'chat-you-are-moderator' => 'Shown when the current user is a moderator. Parameters:
* $1 - GENDER of the current user
See also:
* {{msg-mw|Chat-user-is-moderator}}',
	'chat-topic' => 'Header shown at the top of [[Special:Chat]], to allow links and policies to be displayed, like you find on many IRC clients',
	'chat-change-preferences' => 'The title of the link taking users to [[Special:Preferences]] to change their chat preferences.',
	'chat-sidebar-online' => 'The header for the chat unit on the sidebar that shows online users on chat.

Used if there are one or more online users, and followed by usernames.',
	'chat-sidebar-join' => 'A link in the sidebar, below the currently active users, linking to [[Special:Chat]].
{{Identical|Join the chat}}',
	'chat-mod-image' => 'The URL of the image to show a user is a moderator',
	'log-name-chat' => 'The name of the chat log.

Also used as page title in [[Special:Log/chat]].

See also:
* {{msg-mw|Chat-topic}}',
	'log-description-chat' => 'Used as description for log in [[Special:Log/chat]].',
	'logentry-chat-send' => 'The user $1 sent the message $4',
	'logentry-chat-kick' => 'The user $1 kicked the user $4
* $1 - the user who kicked, may need GENDER
* $2 - the user who was kicked, no need GENDER',
	'log-show-hide-chat' => 'For [[Special:Log]]. Parameters:
* $1 - {{msg-mw|Show}} or {{msg-mw|Hide}}
{{Related|Log-show-hide}}',

'log-description-privatechat' => 'Used as description for log in [[Special:Log/privatechat]].',
'logentry-privatechat-send' => 'The user $1 sent the private message $4, to the user $5',
'log-show-hide-privatechat' => 'For [[Special:Log]]. Parameters:
* $1 - {{msg-mw|Show}} or {{msg-mw|Hide}}
{{Related|Log-show-hide}}',

	'group-chatmod' => '{{doc-group|chatmod}}',
	'group-chatmod-member' => '{{doc-group|forumadmin|member}}',
	'grouppage-chatmod' => '{{doc-group|forumadmin|page}}',
	'group-blockedfromchat' => '{{doc-group|blockedfromchat}}',
	'group-blockedfromchat-member' => '{{doc-group|blockedfromchat|member}}',
	'grouppage-blockedfromchat' => '{{doc-group|blockedfromchat|page}}',
	'right-mediawikichat-chat' => '{{doc-right|mediawikichat-chat}}',
	'right-mediawikichat-modchat' => '{{doc-right|mediawikichat-modchat}}',
	'tog-chat-ping-mention' => 'Label for checkbox for whether to play a sound on receiving mentions.
{{Related|Tog-chat-ping}}',
	'tog-chat-ping-pm' => 'Label for checkbox for whether to play a sound on receiving private messages.
{{Related|Tog-chat-ping}}',
	'tog-chat-ping-message' => 'Label for checkbox for whether to play a sound on receiving normal messages.
{{Related|Tog-chat-ping}}',
	'tog-chat-ping-message' => 'Label for checkbox for whether to use chat in fullscreen.',
	'prefs-chat' => 'Header for the chat preferences at [[Special:Preferences]].
{{Identical|Chat}}',
);

/** Arabic (العربية)
 * @author Tarawneh
 */
$messages['ar'] = array(
	'chat-sounds' => 'شغّل الاصوات',
	'chat-flood' => 'لا يمكن إرسال الرسائل بهذه السرعة، الرجاء التريث قبل إرسال المزيد.',
	'chat-change-preferences' => 'تعديل تفضيلاتك الخاصة بالدردشة',
	'tog-chat-ping-mention' => 'أصدر صوت عند ذكرك من قبل شخص ما',
	'tog-chat-ping-pm' => 'أصدر صوت عند تلقي الرسائل الخاصة',
	'tog-chat-ping-message' => 'أصدر صوت عند تلقي الرسائل العادية',
	'prefs-chat' => 'دردشة',
);

/** Bikol Central (Bikol Central)
 * @author Geopoet
 */
$messages['bcl'] = array(
	'prefs-chat' => 'Makisatsatan',
);

/** Bengali (বাংলা)
 * @author Aftab1995
 * @author Tauhid16
 */
$messages['bn'] = array(
	'chat' => 'আড্ডা',
	'chat-type-your-message' => 'আপনার বার্তা লিখুন',
	'chat-type-your-private-message' => 'আপনার ব্যক্তিগত বার্তাটি লিখুন',
	'chat-no-other-users' => 'আর কোনো ব্যবহারকারী আড্ডায় নেই',
	'chat-blocked-from-chat' => 'আপনি এই আড্ডা থেকে অবরুদ্ধ।',
	'chat-not-allowed' => 'আপনার বার্তা প্রেরণের অনুমতি নেই, প্রথমে প্রবেশ করার চেষ্টা করুন।',
	'chat-just-now' => 'এইমাত্র',
	'chat-a-minute-ago' => 'এক মিনিট আগে',
	'chat-yesterday' => 'গতকাল',
	'chat-kick' => 'পদাঘাত',
	'chat-block' => 'অবরুদ্ধ',
	'chat-youve-been-blocked' => 'আপনি $1-এর দ্বারা {{GENDER:$2|অবরুদ্ধ}} হয়েছেন।',
	'chat-you-blocked' => 'আপনি $1কে {{GENDER:$2|অবরুদ্ধ}} করেছেন।',
	'chat-blocked' => '$1 $2 কে {{GENDER:$3|অবরুদ্ধ}} করেছে।',
	'chat-unblocked' => '$1 $2-কে {{GENDER:$3|অবরুদ্ধ}} করেছে।',
	'chat-left' => '$1 আড্ডা চেড়ে {{GENDER:$2|চলে}} গেছেন।',
	'chat-private-message' => 'ব্যক্তিগত বার্তায় ক্লিক করুন',
	'chat-topic' => 'আপনাকে {{SITENAME}} আড্ডায় স্বাগতম। ([[Special:Log/chat|আড্ডার লগ]])',
	'chat-sidebar-online' => 'আড্ডায় অনলাইন ব্যবহারকারীগণ:',
	'chat-sidebar-join' => 'আড্ডায় যোগ দিন',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => 'আড্ডার লগ',
	'log-show-hide-chat' => 'আড্ডার লগ $1',
	'group-chatmod' => 'আড্ডা নিয়ামকসমূহ',
	'group-blockedfromchat' => 'ব্যবহারকারী আড্ডা থেকে অবরুদ্ধ',
	'group-blockedfromchat-member' => '{{GENDER:$1|আড্ডা থেকে অবরুদ্ধ}}',
	'prefs-chat' => 'আড্ডা',
);

/** Catalan (català)
 * @author Toniher
 */
$messages['ca'] = array(
	'log-name-chat' => 'Registre de xat',
);

/** Chechen (нохчийн)
 * @author Умар
 */
$messages['ce'] = array(
	'chat' => 'Чат',
	'chat-type-your-message' => 'Язбе хьай хаам',
	'chat-type-your-private-message' => 'Язбе хьай хаам',
	'chat-no-other-users' => 'Чата чохь кхин декъашхой бац',
	'chat-blocked-from-chat' => 'Хьона хӀокху чата чохь блоктоьхна',
	'chat-sounds' => 'Лакхийта аз',
	'chat-too-long' => 'Иза хаам тӀех беха бара, бахьийта жимо хаам.',
	'chat-just-now' => 'хӀинца',
	'chat-kick' => 'кхосса',
	'chat-block' => 'блоктоха',
	'chat-you-kicked' => 'Ахьа {{GENDER:$2|кхоьссина}} $1.',
	'chat-kicked' => '$1 {{GENDER:$3||кхоьссина}} $2.',
	'chat-you-blocked' => 'Ахьа {{GENDER:$2|блоктоьхна}} $1.',
	'chat-blocked' => '$1 {{GENDER:$3|блоктоьхна}} $2.',
	'chat-you-unblocked' => 'Ахьа {{GENDER:$2|блоктоьхна}} $1.',
	'chat-unblocked' => '$1 {{GENDER:$3||блокдӀаяьккхина}} $2.',
	'chat-left' => '$1 {{GENDER:$2|араваьлла|араяьлла}} чат чура.',
	'chat-private-message' => 'тӀетаӀ йе хьай хаам дӀабахьийта',
	'chat-user-is-moderator' => 'ХӀара декъашхо — {{GENDER:$1|модератор ву|модератор ю}}.',
	'chat-you-are-moderator' => 'Хьо — {{GENDER:$1|модератор ву|модератор ю}}.',
	'chat-topic' => 'Марша догӀийла чата чу {{SITENAME}}. ([[Special:Log/chat|чатан тептар]])',
	'chat-change-preferences' => 'Чатан гӀирс хийца',
	'chat-sidebar-online' => 'Чат лелораг',
	'chat-sidebar-join' => 'Чатах кхета',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => 'Чатан лог',
	'log-show-hide-chat' => '$1 чатан тептар',
	'group-chatmod-member' => '{{GENDER:$1|чатан модератор}}',
	'right-mediawikichat-chat' => 'Лела йе Special:Chat',
	'prefs-chat' => 'Чат',
);

/** German (Deutsch)
 * @author George Barnick
 * @author GeorgeBarnick
 * @author Metalhead64
 */
$messages['de'] = array(
	'chat' => 'Chat',
	'chat-desc' => 'Ergänzt einen leichtgewichtigen Chat-Client und -Server',
	'chat-type-your-message' => 'Gib deine Nachricht ein',
	'chat-type-your-private-message' => 'Gib deine private Nachricht ein',
	'chat-no-other-users' => 'Keine anderen Benutzer im Chat',
	'chat-blocked-from-chat' => 'Du wurdest für diesen Chat gesperrt.',
	'chat-not-allowed' => 'Du bist nicht berechtigt zu chatten. Versuche zuerst, dich anzumelden.',
	'chat-sounds' => 'Sounds abspielen',
	'chat-flood' => 'Du kannst so schnell keine Nachrichten versenden. Bitte warte, bevor du weitere Nachrichten versendest.',
	'chat-too-long' => 'Diese Nachricht war zu lang. Bitte sende eine kürzere Nachricht.',
	'chat-just-now' => 'gerade eben',
	'chat-a-minute-ago' => 'vor einer Minute',
	'chat-yesterday' => 'gestern',
	'chat-kick' => 'hinauswerfen',
	'chat-block' => 'sperren',
	'chat-youve-been-kicked' => 'Du wurdest von $1 {{GENDER:$2|hinausgeworfen}}. Lade zum Chatten die Seite erneut.',
	'chat-you-kicked' => 'Du hast $1 {{GENDER:$2|hinausgeworfen}}',
	'chat-kicked' => '$1 hat $2 {{GENDER:$3|hinausgeworfen}}',
	'chat-youve-been-blocked' => 'Du wurdest von $1 {{GENDER:$2|gesperrt}}.',
	'chat-you-blocked' => 'Du hast $1 {{GENDER:$2|gesperrt}}',
	'chat-blocked' => '$1 {{GENDER:$3|sperrte}} $2',
	'chat-you-unblocked' => 'Du hast $1 {{GENDER:$2|entsperrt}}',
	'chat-unblocked' => '$1 hat $2 {{GENDER:$3|freigegeben}}',
	'chat-joined' => '$1 ist dem Chat {{GENDER:$2|beigetreten}}',
	'chat-left' => '$1 hat den Chat {{GENDER:$2|verlassen}}',
	'chat-private-message' => 'für private Nachricht klicken',
	'chat-user-is-moderator' => '{{GENDER:$1|Dieser Benutzer ist ein Moderator|Diese Benutzerin ist eine Moderatorin}}',
	'chat-you-are-moderator' => 'Du bist {{GENDER:$1|ein Moderator|eine Moderatorin}}',
	'chat-topic' => 'Willkommen im {{SITENAME}}-Chat. ([[Special:Log/chat|Chat-Logbuch]])',
	'chat-change-preferences' => 'Chat-Einstellungen ändern',
	'chat-sidebar-online' => 'Onlinebenutzer im Chat:',
	'chat-sidebar-join' => 'Dem Chat beitreten',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => 'Chat-Logbuch',
	'log-description-chat' => 'Dieses Logbuch protokolliert von MediaWikiChat versandte Nachrichten und Hinauswerfungen von Benutzern.',
	'logentry-chat-send' => '$1: $4',
	'logentry-chat-kick' => '$1 hat $4 {{GENDER:$1|hinausgeworfen}}',
	'log-show-hide-chat' => 'Chat-Logbuch $1',
	'group-chatmod' => 'Chatmoderatoren',
	'group-chatmod-member' => '{{GENDER:$1|Chat-Moderator|Chat-Moderatorin}}',
	'grouppage-chatmod' => '{{ns:project}}:Chatmoderatoren',
	'group-blockedfromchat' => 'Gesperrte Chat-Benutzer',
	'group-blockedfromchat-member' => '{{GENDER:$1|Gesperrt für den Chat}}',
	'grouppage-blockedfromchat' => '{{ns:project}}:Gesperrte Chat-Benutzer',
	'right-mediawikichat-chat' => 'Special:Chat verwenden',
	'right-mediawikichat-modchat' => 'Benutzer in Special:Chat sperren und hinauswerfen (falls aktiviert)',
	'tog-chat-ping-mention' => 'Einen Sound beim Erhalt von Erwähnungen abspielen',
	'tog-chat-ping-pm' => 'Einen Sound beim Erhalt privater Nachrichten abspielen',
	'tog-chat-ping-message' => 'Einen Sound beim Erhalt normaler Nachrichten abspielen',
	'prefs-chat' => 'Chat',
);

/** British English (British English)
 * @author SirComputer
 */
$messages['en-gb'] = array(
	'chat' => 'Chat',
	'chat-desc' => 'Provides a lightweight chat client and server',
	'chat-type-your-message' => 'Type your message',
	'chat-type-your-private-message' => 'Type your private message',
	'chat-no-other-users' => 'No other users on chat',
);

/** Spanish (español)
 * @author Fitoschido
 * @author GeorgeBarnick
 * @author Sethladan
 */
$messages['es'] = array(
	'chat' => 'Charla',
	'chat-desc' => 'Proporciona un cliente y servidor de chat ligero',
	'chat-type-your-message' => 'Escribe tu mensaje',
	'chat-type-your-private-message' => 'Escribe tu mensaje privado',
	'chat-no-other-users' => 'No hay más usuarios en el chat',
	'chat-blocked-from-chat' => 'Estás bloqueado en este chat.',
	'chat-not-allowed' => 'Intenta iniciar sesión primero antes de charlar.',
	'chat-sounds' => 'Activar sonidos',
	'chat-flood' => 'No puedes enviar mensajes tan rápido, vaquero.',
	'chat-just-now' => 'ahora mismo',
	'chat-a-minute-ago' => 'hace un minuto',
	'chat-yesterday' => 'ayer',
	'chat-kick' => 'expulsar',
	'chat-block' => 'bloquear',
	'chat-youve-been-kicked' => '$1 te ha {{GENDER:$2|expulsado}}. Actualiza la página para charlar.',
	'chat-you-kicked' => 'Has {{GENDER:$2|expulsado}} a $1.',
	'chat-kicked' => '$1 {{GENDER:$3|expulsó}} a $2.',
	'chat-youve-been-blocked' => '$1 te ha {{GENDER:$2|bloqueado}}.',
	'chat-you-blocked' => 'Has {{GENDER:$2|bloqueado}} a $1.',
	'chat-blocked' => '$1 {{GENDER:$3|bloqueó}} a $2.',
	'chat-you-unblocked' => 'Has {{GENDER:$2|desbloqueado}} a $1.',
	'chat-unblocked' => '$1 {{GENDER:$3|desbloqueó}} a $2.',
	'chat-joined' => '$1 se ha {{GENDER:$2|unido}} a la charla.',
	'chat-left' => '$1 ha {{GENDER:$2|abandonado}} la charla.',
	'chat-private-message' => '(mensaje privado)', # Fuzzy
	'chat-user-is-moderator' => 'Est{{GENDER:$1|e usuario|a usuaria}} modera el chat.',
	'chat-you-are-moderator' => 'Eres {{GENDER:$1|un moderador|una moderadora}}.',
	'chat-topic' => 'Te damos la bienvenida al chat de {{SITENAME}}. ([[Special:Log/chat|registro del chat]])',
	'chat-sidebar-online' => 'Usuarios conectados al chat:',
	'chat-sidebar-join' => 'Únete a la charla',
	'log-name-chat' => 'Registro de la charla',
	'log-show-hide-chat' => '$1 registro de chat',
	'group-chatmod' => 'Moderadores del chat',
	'group-chatmod-member' => '{{GENDER:$1|moderador|moderadora}} del chat',
	'grouppage-chatmod' => '{{ns:project}}:Moderadores del chat',
	'group-blockedfromchat' => 'Usuarios bloqueados del chat',
	'right-mediawikichat-chat' => 'Uso Special:Chat',
);

/** Persian (فارسی)
 * @author Armin1392
 */
$messages['fa'] = array(
	'chat' => 'گفتگو',
	'chat-desc' => 'ارائهٔ یک مشتری گفتگوی سبک وزن و سرور',
	'chat-type-your-message' => 'پیام خود را تایپ کنید',
	'chat-type-your-private-message' => 'پیام خصوصی خود را تایپ کنید',
	'chat-no-other-users' => 'کاربران دیگری در گفتگو نیستند',
	'chat-blocked-from-chat' => 'شما از این گفتگو مسدود شده‌اید.',
	'chat-not-allowed' => 'شما مجاز به گفتگو نیستید، ابتدا سعی کنید وارد شوید',
	'chat-sounds' => 'پخش صداها',
	'chat-flood' => 'شما نمی‌توانید به آن سریعی پیام‌ها را ارسال کنید، لطفاً قبل از ارسال بیشتر صبر کنید.',
	'chat-too-long' => 'آن پیام بسیار طولانی بود، لطفاً پیام کوتاه‌تری ارسال کنید.',
	'chat-just-now' => 'هم‌اکنون',
	'chat-a-minute-ago' => 'یک دقیقه پیش',
	'chat-yesterday' => 'دیروز',
	'chat-kick' => 'ضربه زدن',
	'chat-block' => 'مسدود',
	'chat-youve-been-kicked' => 'شما توسط $1 {{GENDER:$2|ضربه}} زده‌اید. صفحه را برای گفتگو تجدید کنید',
	'chat-you-kicked' => 'شما $1 را {{GENDER:$2|ضربه زدید}}',
	'chat-kicked' => '$1 {{GENDER:$3|ضربه زدید}} $2',
	'chat-youve-been-blocked' => 'شما توسط $1 {{GENDER:$2|مسدود شده‌اید}}.',
	'chat-you-blocked' => 'شما {{GENDER:$2|مسدود شدید}} $1',
	'chat-blocked' => '$1 {{GENDER:$3|مسدود شده}} $2',
	'chat-you-unblocked' => 'شما {{GENDER:$2|مسدود نشدید}} $1',
	'chat-unblocked' => '$1 {{GENDER:$3|مسدود نشده}} $2',
	'chat-joined' => '$1 به گفتگو {{GENDER:$2|پیوسته شده‌است}}',
	'chat-left' => '$1 گفتگو {{GENDER:$2|ترک کرده‌است}}',
	'chat-private-message' => 'برای پیغام خصوصی کلیک کنید',
	'chat-user-is-moderator' => 'این کاربر یک مدیر {{GENDER:$1|هست}}',
	'chat-you-are-moderator' => 'شما یک مدیر {{GENDER:$1|هستید}}',
	'chat-topic' => 'خوش آمدید به {{SITENAME}} گفتگوی. ([[Special:Log/chat|ورود به گفتگو]])',
	'chat-change-preferences' => 'ترجیحات گفتگوی خود را تغییر دهید',
	'chat-sidebar-online' => 'کاربران آنلاین در گفتگو:',
	'chat-sidebar-join' => 'پیوستن به گفتگو',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => 'ورود به گفتگو',
	'log-description-chat' => 'پیغام‌ها توسط گفتگوی مدیاویکی، به خوبی ضربات کاربران فرستاده شده',
	'logentry-chat-kick' => '$1 {{GENDER:$1|ضربه زده}} $4',
	'log-show-hide-chat' => '$1 ورود به گفتگو',
	'group-chatmod' => 'مدیران گفتگو',
	'group-chatmod-member' => '{{GENDER:$1|مدیر گفتگو}}',
	'grouppage-chatmod' => '{{ns:project}}:مدیران گفتگو',
	'group-blockedfromchat' => 'کاربران مسدود شده از گفتگو',
	'group-blockedfromchat-member' => '{{GENDER:$1|از گفتگو مسدود شده}}',
	'grouppage-blockedfromchat' => '{{ns:project}}:کاربران مسدود شده از گفتگو',
	'right-mediawikichat-chat' => 'استفادهٔ‌خاص:‌ گفتگو',
	'right-mediawikichat-modchat' => 'مسدود و ضربهٔ (اگر فعال باشد) کاربران از ویژه: گفتگو',
	'tog-chat-ping-mention' => 'پخش صدا در اشارات دریافتی',
	'tog-chat-ping-pm' => 'پخش صدا در پیام‌های خصوصی دریافتی',
	'tog-chat-ping-message' => 'پخش صدا در پیام‌های عادی دریافتی',
	'prefs-chat' => 'گفتگو',
);

/** Finnish (suomi)
 * @author Stryn
 */
$messages['fi'] = array(
	'chat-just-now' => 'juuri nyt',
	'chat-a-minute-ago' => 'minuutti sitten',
	'chat-yesterday' => 'eilen',
	'chat-block' => 'estä',
);

/** French (français)
 * @author Gomoko
 * @author Verdy p
 */
$messages['fr'] = array(
	'chat' => 'Discussion',
	'chat-desc' => 'Fournit un client et un serveur de discussion légers',
	'chat-type-your-message' => 'Tapez votre message',
	'chat-type-your-private-message' => 'Tapez votre message privé',
	'chat-no-other-users' => 'Aucun autre utilisateur sur la discussion',
	'chat-blocked-from-chat' => 'Vous avez été bloqué depuis cette discussion.',
	'chat-not-allowed' => 'Vous n’êtes pas autorisé à discuter, essayez d’abord de vous connecter',
	'chat-sounds' => 'Jouer des sons',
	'chat-flood' => 'Vous ne pouvez pas envoyer de messages aussi rapidement, veuillez attendre avant d’en envoyer davantage.',
	'chat-too-long' => 'Ce message était trop long, veuillez en envoyer un plus court.',
	'chat-just-now' => 'à l’instant',
	'chat-a-minute-ago' => 'il y a une minute',
	'chat-yesterday' => 'hier',
	'chat-kick' => 'éjecter',
	'chat-block' => 'bloquer',
	'chat-youve-been-kicked' => 'Vous avez été {{GENDER:$2|éjecté|éjectée}} par $1. Rafraîchissez la page pour discuter',
	'chat-you-kicked' => 'Vous {{GENDER:$2|avez éjecté}} $1',
	'chat-kicked' => '$1 {{GENDER:$3|a éjecté}} $2',
	'chat-youve-been-blocked' => 'Vous avez été {{GENDER:$2|bloqué|bloquée}} par $1.',
	'chat-you-blocked' => 'Vous {{GENDER:$2|avez bloqué}} $1',
	'chat-blocked' => '$1 {{GENDER:$3|a bloqué}} $2',
	'chat-you-unblocked' => 'Vous {{GENDER:$2|avez débloqué}} $1',
	'chat-unblocked' => '$1 {{GENDER:$3|a débloqué}} $2',
	'chat-joined' => '$1 a {{GENDER:$2|rejoint}} la discussion',
	'chat-left' => '$1 a {{GENDER:$2|quitté}} la discussion',
	'chat-private-message' => 'cliquer pour un message privé',
	'chat-user-is-moderator' => 'Cet utilisateur {{GENDER:$1|est}} un modérateur',
	'chat-you-are-moderator' => 'Vous {{GENDER:$1|êtes}} un modérateur',
	'chat-topic' => 'Bienvenue sur la discussion de {{SITENAME}}. ([[Special:Log/chat|journal de discussion]])',
	'chat-change-preferences' => 'Modifier vos préférences de discussion',
	'chat-sidebar-online' => 'Utilisateurs connectés sur la discussion :',
	'chat-sidebar-join' => 'Rejoindre la discussion',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => 'Journal de discussion',
	'log-description-chat' => 'Messages envoyés par MediaWikiChat, ainsi que les éjections d’utilisateur',
	'logentry-chat-send' => '$1 : $4',
	'logentry-chat-kick' => '$1 {{GENDER:$1|a éjecté}} $4',
	'log-show-hide-chat' => '$1 journal de discussion',
	'group-chatmod' => 'Modérateurs de discussion',
	'group-chatmod-member' => '{{GENDER:$1|modérateur|modératrice}} de discussion',
	'grouppage-chatmod' => '{{ns:project}}:Chat moderators',
	'group-blockedfromchat' => 'Utilisateurs bloqués depuis la discussion',
	'group-blockedfromchat-member' => '{{GENDER:$1|bloqué|bloquée}} depuis la discussion',
	'grouppage-blockedfromchat' => '{{ns:project}}:Users blocked from chat',
	'right-mediawikichat-chat' => 'Utiliser Special:Chat',
	'right-mediawikichat-modchat' => 'Bloquer et éjecter (si activé) des utilisateurs depuis Special:Chat',
	'tog-chat-ping-mention' => 'Jouer un son lors de la réception de mentions',
	'tog-chat-ping-pm' => 'Jouer un son lors de la réception de messages privés',
	'tog-chat-ping-message' => 'Jouer un son lors de la réception de messages normaux',
	'prefs-chat' => 'Discussion',
);

/** Hebrew (עברית)
 * @author Danny-w
 * @author Mbkv717
 * @author איתן96
 * @author תומר ט
 */
$messages['he'] = array(
	'chat' => "צ'אט",
	'chat-type-your-message' => 'הקלידו את הודעתכם',
	'chat-type-your-private-message' => 'הקלידו את הודעתכם הפרטית',
	'chat-no-other-users' => "אין משתמשים אחרים בצ'אט",
	'chat-not-allowed' => "אין לך הרשאות לשוחח בצ'אט. יש לנסות להתחבר לחשבון.",
	'chat-sounds' => 'נגן שמע',
	'chat-flood' => 'אינך יכול לשלוח הודעות במהירות כזאת, נא המתן לפני שליחת ההודעה הבאה.',
	'chat-just-now' => 'לפני זמן קצר',
	'chat-a-minute-ago' => 'לפני דקה',
	'chat-yesterday' => 'אתמול',
	'chat-block' => 'חסום',
	'chat-change-preferences' => "שנה את העדפות הצ'אט שלך",
	'chat-sidebar-online' => "משתמשים מחוברים בצ'אט:",
	'chat-sidebar-join' => "הצטרפות לצ'אט",
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => "יומן צ'אט",
	'group-chatmod' => "מנהלי הצ'אט",
	'grouppage-chatmod' => "{{ns:project}}:מנהלי הצ'אט",
	'group-blockedfromchat' => "המשתמשים נחסמו לצ'אט",
	'grouppage-blockedfromchat' => "{{ns:project}}:המשתמשים נחסמו לצ'אט",
	'tog-chat-ping-mention' => 'השמע צליל בעת קבלת תזכיר',
	'tog-chat-ping-pm' => 'השמע צליל בעת קבלת הודעה פרטית',
	'tog-chat-ping-message' => 'השמע צליל בעת קבלת הודעה רגילה',
	'prefs-chat' => "צ'אט",
);

/** Hindi (हिन्दी)
 * @author Vivek Rai
 */
$messages['hi'] = array(
	'chat-sounds' => 'ध्वनियाँ चलाएँ',
	'chat-flood' => 'आप इतनी तेजी से सन्देश नहीं भेज सकते। कृपया प्रतीक्षा करें।',
	'chat-too-long' => 'वह संदेश बहुत लंबा था, कृपया एक छोटा संदेश भेजें।',
	'chat-change-preferences' => 'अपनी चैट वरीयताएँ बदलें',
	'tog-chat-ping-mention' => 'उल्लेख प्राप्त करने पर कोई ध्वनि चलाएँ',
	'tog-chat-ping-pm' => 'निजी संदेश प्राप्त करने पर कोई ध्वनि चलाएँ',
	'tog-chat-ping-message' => 'सामान्य संदेश प्राप्त करने पर कोई ध्वनि चलाएँ',
	'prefs-chat' => 'चैट',
);

/** Armenian (Հայերեն)
 * @author Vadgt
 */
$messages['hy'] = array(
	'chat' => 'Վիճակագրություն',
	'chat-type-your-message' => 'Մուտքագրեք ձեր հաղորդագրությունը',
	'chat-a-minute-ago' => 'մի քանի րոպե առաջ',
	'chat-minutes-ago' => '{{PLURAL:$1|$1 րոպե|$1 րոպե}} առաջ', # Fuzzy
);

/** Japanese (日本語)
 * @author Shirayuki
 */
$messages['ja'] = array(
	'chat' => 'チャット',
	'chat-type-your-message' => 'メッセージを入力',
	'chat-type-your-private-message' => '非公開メッセージを入力',
	'chat-no-other-users' => 'チャットには他の利用者はいません',
	'chat-blocked-from-chat' => 'あなたはこのチャットからブロックされています。',
	'chat-not-allowed' => 'チャットの使用を許可されていません。ログインしてからお試しください。',
	'chat-a-minute-ago' => '1分前',
	'chat-yesterday' => '昨日',
	'chat-block' => 'ブロック',
	'chat-youve-been-blocked' => '$1 があなたを{{GENDER:$2|ブロックしました}}。',
	'chat-you-blocked' => '$1 を{{GENDER:$2|ブロックしました}}。',
	'chat-blocked' => '$1 が $2 を{{GENDER:$3|ブロックしました}}。',
	'chat-you-unblocked' => '$1 を{{GENDER:$2|ブロック解除しました}}。',
	'chat-unblocked' => '$1 が $2 を{{GENDER:$3|ブロック解除しました}}。',
	'chat-joined' => '$1 がチャットに{{GENDER:$2|入室しました}}。',
	'chat-left' => '$1 がチャットから{{GENDER:$2|退室しました}}。',
	'chat-private-message' => 'クリックで非公開メッセージ',
	'chat-user-is-moderator' => 'この利用者はモデレーター{{GENDER:$1|です}}',
	'chat-you-are-moderator' => 'あなたはモデレーター{{GENDER:$1|です}}',
	'chat-topic' => '{{SITENAME}}のチャットへようこそ。([[Special:Log/chat|チャット記録]])',
	'chat-change-preferences' => 'チャットの個人設定を変更',
	'chat-sidebar-online' => 'チャットでオンラインの利用者:',
	'chat-sidebar-join' => 'チャットに入室',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => 'チャット記録',
	'log-show-hide-chat' => 'チャット記録を$1',
	'group-chatmod' => 'チャット モデレーター',
	'group-chatmod-member' => '{{GENDER:$1|チャット モデレーター}}',
	'grouppage-chatmod' => '{{ns:project}}:チャット モデレーター',
	'right-mediawikichat-chat' => 'Special:Chat を使用',
	'tog-chat-ping-mention' => '言及された際にサウンドを再生',
	'tog-chat-ping-pm' => '非公開メッセージが届いた際にサウンドを再生',
	'tog-chat-ping-message' => '通常のメッセージが届いた際にサウンドを再生',
	'prefs-chat' => 'チャット',
);

/** Georgian (ქართული)
 * @author GeorgeBarnick
 */
$messages['ka'] = array(
	'chat' => 'სტატისტიკა',
);

/** Korean (한국어)
 * @author Freebiekr
 * @author Priviet
 */
$messages['ko'] = array(
	'chat' => '채팅',
	'chat-desc' => '가벼운 채팅 클라이언트 및 서버',
	'chat-type-your-message' => '메시지 입력',
	'chat-type-your-private-message' => '비공개 메시지를 입력',
	'chat-no-other-users' => '채팅 중인 사용자가 없음',
	'chat-blocked-from-chat' => '이 채팅에서 차단되었습니다.',
	'chat-not-allowed' => '채팅을 할 수 없습니다. 먼저 로그인해 보세요.',
	'chat-sounds' => '소리 재생',
	'chat-flood' => '메시지를 보내는 속도가 너무 빠릅니다. 메시지를 보내려면 잠시 기다려주세요.',
	'chat-too-long' => '메시지가 너무 깁니다. 더 짧은 메시지를 보내주세요.',
	'chat-just-now' => '바로 조금 전',
	'chat-a-minute-ago' => '1분 전',
	'chat-yesterday' => '어제',
	'chat-kick' => '추방',
	'chat-block' => '차단',
	'chat-youve-been-kicked' => '$1 사용자가 당신을 {{GENDER:$2|추방}}했습니다. 페이지를 다시 불러들여 채팅하십시오.',
	'chat-you-kicked' => '$1 사용자를 {{GENDER:$2|추방했습니다}}.',
	'chat-kicked' => '$1 사용자가 $2 사용자를 {{GENDER:$3|추방했습니다}}.',
	'chat-youve-been-blocked' => '$1 사용자가 당신을 {{GENDER:$2|차단했습니다}}.',
	'chat-you-blocked' => '$1 사용자를 {{GENDER:$2|차단했습니다}}.',
	'chat-blocked' => '$1 사용자가  $2 사용자를 {{GENDER:$3|차단했습니다}}.',
	'chat-you-unblocked' => '$1 사용자의 {{GENDER:$2|차단을 해제했습니다}}.',
	'chat-unblocked' => '$1 사용자가 $2 사용자의 {{GENDER:$3|차단을 해제했습니다}}.',
	'chat-joined' => '$1 사용자가 채팅에 {{GENDER:$2|참여했습니다}}.',
	'chat-left' => '$1 사용자가 채팅에서 {{GENDER:$2|나갔습니다}}.',
	'chat-private-message' => '비공개 메시지',
	'chat-user-is-moderator' => '이 사용자는 관리자{{GENDER:$1|입니다}}.',
	'chat-you-are-moderator' => '당신은 관리자{{GENDER:$1|입니다}}.',
	'chat-topic' => '{{SITENAME}} 채팅에에 오신 것을 환영합니다. ([[Special:Log/chat|채팅 기록]])',
	'chat-change-preferences' => '채팅 설정 바꾸기',
	'chat-sidebar-online' => '채팅에 참여한 사용자:',
	'chat-sidebar-join' => '채팅에 참여',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => '채팅 기록',
	'log-description-chat' => '미디어위키 채팅에서 보낸 메시지와 사용자 추방',
	'logentry-chat-kick' => '$1 사용자가 $4 사용자를 {{GENDER:$1|추방했습니다}}.',
	'log-show-hide-chat' => '채팅 기록 $1',
	'group-chatmod' => '채팅 관리자',
	'group-chatmod-member' => '{{GENDER:$1|채팅 관리자}}',
	'grouppage-chatmod' => '{{ns:project}}:채팅 관리자',
	'group-blockedfromchat' => '채팅이 차단된 사용자',
	'group-blockedfromchat-member' => '{{GENDER:$1|채팅이 차단됨}}',
	'grouppage-blockedfromchat' => '{{ns:project}}:채팅이 차단된 사용자',
	'right-mediawikichat-chat' => 'Special:Chat 사용',
	'right-mediawikichat-modchat' => 'Special:Chat에서 사용자를 차단하고 추방(활성화되어 있을 경우에만)',
	'tog-chat-ping-mention' => '멘션을 받았을 때 소리를 재생',
	'tog-chat-ping-pm' => '개인 메시지를 받았을 때 소리를 재생',
	'tog-chat-ping-message' => '보통 메시지를 받았을 때 소리를 재생',
	'prefs-chat' => '대화',
);

/** Luxembourgish (Lëtzebuergesch)
 * @author Robby
 */
$messages['lb'] = array(
	'chat' => 'Chat',
	'chat-type-your-message' => 'Tippt Äre Message',
	'chat-type-your-private-message' => 'Tippt Äre private Message',
	'chat-no-other-users' => 'Keng aner Benotzer am Chat',
	'chat-blocked-from-chat' => 'Dir gouft an dësem Chat gespaart.',
	'chat-not-allowed' => "Dir däerft net chatten, probéiert Iech d'éischt anzeloggen.",
	'chat-sounds' => 'Geräischer ofspillen',
	'chat-flood' => 'Dir däerft Messagen net esou séier schécken, waart w.e.g. e bësse bis Dir der méi schéckt.',
	'chat-too-long' => 'Dëse Message war ze laang, schéckt w.e.g. méi e kuerze Message.',
	'chat-just-now' => 'grad elo',
	'chat-a-minute-ago' => 'virun enger Minutt',
	'chat-yesterday' => 'gëschter',
	'chat-kick' => 'erausgeheien',
	'chat-block' => 'spären',
	'chat-youve-been-blocked' => 'Dir gouft {{GENDER:$2|vum}} $1 gespaart.',
	'chat-you-blocked' => "Dir hutt {{GENDER:$2|den |d'}}$1 gespaart",
	'chat-you-unblocked' => 'Dir hutt dem {{GENDER:$2|seng}}$1 Spär opgehuewen.',
	'chat-private-message' => 'klickt fir privat Messagen',
	'chat-user-is-moderator' => 'Dëse Benotzer {{GENDER:$1|ass}} e Moderateur.',
	'chat-change-preferences' => 'Ännert Är Chat-Astellungen',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'group-blockedfromchat' => 'Benotzer déi fir den Chat gespaart sinn',
	'right-mediawikichat-chat' => 'Special:Chat benotzen',
	'prefs-chat' => 'Chat',
);

/** Latvian (latviešu)
 * @author Papuass
 */
$messages['lv'] = array(
	'chat-a-minute-ago' => 'pirms minūtes',
	'chat-yesterday' => 'vakar',
);

/** Basa Banyumasan (Basa Banyumasan)
 * @author StefanusRA
 */
$messages['map-bms'] = array(
	'chat' => 'Dopokan',
	'chat-blocked-from-chat' => 'Rika wis diblokir sekang dopokan kiye.',
	'chat-a-minute-ago' => 'semenit kepungkur',
	'chat-kicked' => '$1 {{GENDER:$3|ditendang}} $2',
	'chat-kick' => 'tendang',
	'chat-user-is-moderator' => 'Pangganggo kiye {{GENDER:$1|kuwe}} moderator',
	'chat-topic' => 'Sugeng rawuh nang dopokan {{SITENAME}}. ([[Special:Log/chat|log dopokan]])',
	'log-name-chat' => 'Log dopokan',
);

/** Malagasy (Malagasy)
 * @author Jagwar
 */
$messages['mg'] = array(
	'chat-sounds' => 'Hamoaka feo',
	'chat-flood' => "Tsy afaka mandefa hafatra haingana hoatr'izany ianao, andraso kely alohan'ny mandefa hafa.",
	'chat-too-long' => 'Lava loatra ilay hafatra, mandefasa hafatra fohifohy kokoa azafady.',
	'chat-change-preferences' => "Hanova ny safidin'ny resaka mifantana",
	'tog-chat-ping-message' => 'Hamoaka feo rehefa mahazo hafatra tsotra',
	'prefs-chat' => 'Resaka',
);

/** Macedonian (македонски)
 * @author Bjankuloski06
 */
$messages['mk'] = array(
	'chat' => 'Разговори во живо',
	'chat-desc' => 'Овозможува лек клиент и опслужувач за разговори во живо',
	'chat-type-your-message' => 'Внесете ја пораката',
	'chat-type-your-private-message' => 'Внесете ја вашата приватна порака',
	'chat-no-other-users' => 'Нема други корисници во разговорот',
	'chat-blocked-from-chat' => 'Блокирани сте од овој разговор во живо.',
	'chat-not-allowed' => 'Немате пристап во разговорот. Прво најавете се',
	'chat-sounds' => 'Пуштај звуци',
	'chat-flood' => 'Не можете да праќате пораки толку бргу. Почекајте, па испратете други.',
	'chat-too-long' => 'Пораката беше предолга. Испратете покуса порака.',
	'chat-just-now' => 'штотуку',
	'chat-a-minute-ago' => 'пред една минута',
	'chat-yesterday' => 'вчера',
	'chat-kick' => 'исфрли',
	'chat-block' => 'блокирај',
	'chat-youve-been-kicked' => '$1 ве {{GENDER:$2|исфрли}}. Превчитајте ја страницата за да продолжите со разговорот',
	'chat-you-kicked' => 'Го {{GENDER:$2|исфрливте}} учесникот $1',
	'chat-kicked' => '$1 го {{GENDER:$3|исфрли}} учесникот $2',
	'chat-youve-been-blocked' => '$1 ве {{GENDER:$2|блокираше}}.',
	'chat-you-blocked' => 'Го {{GENDER:$2|блокиравте}} корисникот $1',
	'chat-blocked' => '$1 го {{GENDER:$3|блокираше}} корисникот $2',
	'chat-you-unblocked' => 'Го {{GENDER:$2|одблокиравте}} учесникот $1',
	'chat-unblocked' => '$1 го {{GENDER:$3|одблокира}} учесникот $2',
	'chat-joined' => '$1 се {{GENDER:$2|приклучи}} на разговорот',
	'chat-left' => '$1 го {{GENDER:$2|напушти}} разговорот',
	'chat-private-message' => 'стиснете за да испратите приватна порака',
	'chat-user-is-moderator' => 'Корисников {{GENDER:$1|е}} модератор',
	'chat-you-are-moderator' => 'Вие {{GENDER:$1|сте}} модератор',
	'chat-topic' => 'Добредојдовте на разговорот во живо на {{SITENAME}}. ([[Special:Log/chat|записник на разговорот]])',
	'chat-change-preferences' => 'Измени нагодувања на разговорот',
	'chat-sidebar-online' => 'Корисници на линија во разговорот:',
	'chat-sidebar-join' => 'Приклучи се на разговорот',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => 'Записник на разговорот',
	'log-description-chat' => 'Пораки испратени од МедијаВикиРазговори, како и исфрлања на корисници',
	'logentry-chat-kick' => '$1 го {{GENDER:$1|исфрли}} учесникот $4',
	'log-show-hide-chat' => '$1 записник',
	'group-chatmod' => 'Модератори на разговори',
	'group-chatmod-member' => '{{GENDER:$1|модератор на разговори}}',
	'grouppage-chatmod' => '{{ns:project}}:Модератори на разговори',
	'group-blockedfromchat' => 'Корисници блокирани од разговор',
	'group-blockedfromchat-member' => '{{GENDER:$1|блокиран од разговор|блокирана од разговор}}',
	'grouppage-blockedfromchat' => '{{ns:project}}:Корисници блокирани од разговор',
	'right-mediawikichat-chat' => 'Користи Специјална:Разговори во живо',
	'right-mediawikichat-modchat' => 'Блокирање и исфрлање (ако е овозможено) на корисници од Специјална:Разговори во живо',
	'tog-chat-ping-mention' => 'Пуштај звуци кога ќе се појават споменувања',
	'tog-chat-ping-pm' => 'Пуштај звуци кога ќе добивам лични пораки',
	'tog-chat-ping-message' => 'Пуштај звуци кога ќе се појават нормални пораки',
	'prefs-chat' => 'Разговор',
);

/** Marathi (मराठी)
 * @author V.narsikar
 */
$messages['mr'] = array(
	'chat' => 'गप्पागोष्टी',
	'chat-desc' => 'कमी क्षमतेचा चॅट क्लायंट व सर्व्हर उपलब्ध करतो',
	'chat-type-your-message' => 'आपला संदेश टंका',
	'chat-type-your-private-message' => 'आपला खाजगी संदेश टंका',
	'chat-no-other-users' => 'चॅटसाठी इतर कोणी सदस्य नाही',
	'chat-blocked-from-chat' => 'आपण या चॅट करण्यापासून रोखल्या गेले आहात.',
	'chat-not-allowed' => 'आपणास चॅट करण्याची परवानगी नाही,सनोंद प्रवेशाचा प्रयत्न करा',
	'chat-sounds' => 'ध्वनी चालू',
	'chat-flood' => 'इतक्या जलद रितीने आपण संदेश पाठवू शकत नाही,अधिक पाठविण्याआधी कृपया थोडे थांबा.',
	'chat-just-now' => 'आत्ताच',
	'chat-a-minute-ago' => 'एका मिनीटापूर्वी',
	'chat-yesterday' => 'काल',
	'chat-kick' => 'लाथाडा',
	'chat-block' => 'प्रतिबंधित करा',
	'chat-youve-been-kicked' => '$1 द्वारे आपण {{GENDER:$2|लाथाडल्या गेले आहात}}.
चॅट करण्यास या पानास तरोताजे करा',
	'chat-you-kicked' => 'आपण $1 ला {{GENDER:$2|लाथाडले आहे}}.',
	'chat-kicked' => '$1 ने $2 ला {{GENDER:$3|लाथाडले}}',
	'chat-youve-been-blocked' => 'आपण $1 द्वारे{{GENDER:$2|प्रतिबंधित आहात}}.',
	'chat-you-blocked' => 'आपण $1 ला {{GENDER:$2|प्रतिबंधित केले आहे}}',
	'chat-blocked' => '$1 ने $2 ला {{GENDER:$3|प्रतिबंधित केले आहे}}',
	'chat-you-unblocked' => 'आपण $1 ला {{GENDER:$2|अप्रतिबंधित केले आहे}}',
	'chat-unblocked' => '$1 ने $2 ला {{GENDER:$3|अप्रतिबंधित केले आहे}}',
	'chat-joined' => '$1 हा चॅटशी {{GENDER:$2|जुळला आहे}}',
	'chat-left' => '$1 चॅटच्या {{GENDER:$2|बाहेर गेला आहे}}',
	'chat-private-message' => 'खाजगी संदेशासाठी टिचका',
	'chat-user-is-moderator' => 'हा सदस्य नियामक (मॉडरेटर) {{GENDER:$1|आहे}}',
	'chat-you-are-moderator' => 'आपण नियामक {{GENDER:$1|आहात}}',
	'chat-topic' => '{{SITENAME}}च्या चॅट वर आपले स्वागत आहे.([[Special:Log/chat|चॅट नोंदी]])',
	'chat-change-preferences' => 'आपल्या गप्पागोष्टींचा पसंतीक्रम बदला',
	'chat-sidebar-online' => 'चॅट करीत असलेले ऑनलाईन सदस्य',
	'chat-sidebar-join' => 'चॅटला जुळा',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => 'चॅटच्या नोंदी',
	'log-description-chat' => 'मिडियाविकी चॅट तसेच सदस्य किक्स द्वारे पाठविण्यात आलेला संदेश',
	'logentry-chat-kick' => '$1 ने $4 ला {{GENDER:$1|लाथाडले}}',
	'log-show-hide-chat' => '$1 चॅट नोंदी',
	'group-chatmod' => 'चॅट नियामक (मॉडरेटर)',
	'group-chatmod-member' => '{{GENDER:$1|चॅट नियामक}}',
	'grouppage-chatmod' => '{{ns:project}}:चॅट नियामक',
	'group-blockedfromchat' => 'सदस्यास चॅट करण्यास प्रतिबंधित आहे',
	'group-blockedfromchat-member' => '{{GENDER:$1|चॅटपासून प्रतिबंधित}}',
	'grouppage-blockedfromchat' => '{{ns:project}}:सदस्यांना चॅटपासून प्रतिबंधित केले आहे',
	'right-mediawikichat-chat' => 'Special:चॅट चा वापर करा',
	'right-mediawikichat-modchat' => 'Special:चॅट मधून सदस्यास प्रतिबंध करा व लाथाडा.',
	'tog-chat-ping-mention' => 'दखलसंदेश मिळाल्यावर ध्वनीसंकेत द्या',
	'tog-chat-ping-pm' => 'खाजगी संदेश मिळाल्यावर ध्वनीसंकेत द्या',
	'tog-chat-ping-message' => 'साधारण संदेश मिळाल्यावर ध्वनीसंकेत द्या',
	'prefs-chat' => 'गप्पागोष्टी',
);

/** Malay (Bahasa Melayu)
 * @author Anakmalaysia
 */
$messages['ms'] = array(
	'chat-not-allowed' => 'Anda tidak dibenarkan untuk bersembang. Cuba log masuk dahulu.',
	'chat-youve-been-kicked' => 'Anda telah {{GENDER:$2|dihalau}} oleh $1. Muat semula halaman untuk bersembang.',
	'chat-you-kicked' => 'Anda telah {{GENDER:$2|menghalau}} $1.',
	'chat-kicked' => '$1 telah {{GENDER:$3|menghalau}} $2.',
	'chat-you-blocked' => 'Anda telah {{GENDER:$2|menyekat}} $1.',
	'chat-blocked' => '$1 telah {{GENDER:$3|menyekat}} $2.',
	'chat-you-unblocked' => 'Anda telah {{GENDER:$2|menyahsekat}} $1.',
	'chat-unblocked' => '$1 telah {{GENDER:$3|menyahsekat}} $2.',
	'chat-joined' => '$1 telah {{GENDER:$2|menyertai}} ruang sembang.',
	'chat-left' => '$1 telah {{GENDER:$2|keluar}} dari ruang sembang.',
	'chat-private-message' => 'klik untuk pesanan peribadi',
	'chat-user-is-moderator' => 'Pengguna ini {{GENDER:$1|ialah}} seorang moderator',
	'chat-you-are-moderator' => 'Anda {{GENDER:$1|adalah}} seorang moderator',
);

/** Dutch (Nederlands)
 * @author Arent
 * @author SPQRobin
 * @author Sjoerddebruin
 * @author Southparkfan
 */
$messages['nl'] = array(
	'chat' => 'Chat',
	'chat-not-allowed' => 'U heeft niet de benodigde rechten om te chatten, probeer eerst in te loggen.',
	'chat-sounds' => 'Geluiden afspelen',
	'chat-just-now' => 'zojuist',
	'chat-a-minute-ago' => 'even geleden',
	'chat-yesterday' => 'gisteren',
	'chat-kick' => 'kick',
	'chat-block' => 'blokkeren',
	'chat-you-blocked' => 'U heeft $1 {{GENDER:$2|geblokkeerd}}.',
	'chat-blocked' => '$1 heeft $2 {{GENDER:$3|geblokkeerd}}.',
	'chat-you-unblocked' => 'U heeft $1 gedeblokkeerd.', # Fuzzy
	'chat-unblocked' => '$1 heeft $2 gedeblokkeerd.', # Fuzzy
	'chat-user-is-moderator' => 'Deze gebruiker is moderator', # Fuzzy
	'chat-you-are-moderator' => 'Jij {{GENDER:$1|bent}} een moderator',
	'chat-topic' => 'Welkom op de chat van {{SITENAME}}. ([[Special:Log/chat|logboek]])',
	'chat-change-preferences' => 'Wijzig uw chatinstellingen',
	'chat-sidebar-online' => 'Online gebruikers in de chat:',
	'chat-sidebar-join' => 'Chat mee',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => 'Chatlogboek',
	'log-show-hide-chat' => 'chatlogboek van $1',
	'group-chatmod' => 'Chatmoderators',
	'group-chatmod-member' => '{{GENDER:$1|chatmoderator}}',
	'grouppage-chatmod' => '{{ns:project}}:Chatmoderators',
	'group-blockedfromchat' => 'Gebruikers geblokkeerd van de chat',
	'group-blockedfromchat-member' => '{{GENDER:$1|geblokkeerd van de chat}}',
	'grouppage-blockedfromchat' => '{{ns:project}}:Gebruikers geblokkeerd van de chat',
	'right-mediawikichat-chat' => 'Gebruik Speciaal:Chat',
	'prefs-chat' => 'Chat',
);

/** Polish (polski)
 * @author Chrumps
 * @author Vuh
 */
$messages['pl'] = array(
	'chat' => 'Czat',
	'chat-type-your-message' => 'Wpisz wiadomość',
	'chat-type-your-private-message' => 'Wpisz wiadomość prywatną',
	'chat-no-other-users' => 'Brak innych użytkowników na czacie',
	'chat-blocked-from-chat' => 'Zostałeś zablokowany na tym czacie.',
	'chat-a-minute-ago' => 'minutę temu',
	'chat-yesterday' => 'wczoraj',
	'chat-block' => 'zablokuj',
	'chat-youve-been-kicked' => '{{GENDER:$2|Zostałeś wyrzucony|Zostałaś wyrzucona}} przez $1. Odśwież stronę czatu.',
	'chat-you-kicked' => 'Ty {{GENDER:$2|wyrzuciłeś|wyrzuciłaś}} $1.',
	'chat-kicked' => '$1 {{GENDER:$3|wyrzucił|wyrzuciła}} $2.',
	'chat-youve-been-blocked' => '{{GENDER:$2|Zostałeś zablokowany|Zostałaś zablokowana}} przez $1.',
	'chat-you-blocked' => 'Ty {{GENDER:$2|zablokowałeś|zablokowałaś}} $1.',
	'chat-blocked' => '$1 {{GENDER:$3|zablokował|zablokowała}} $2.',
	'chat-you-unblocked' => 'Ty {{GENDER:$2|odblokowałeś|odblokowałaś}} $1.',
	'chat-unblocked' => '$1 {{GENDER:$3|odblokował|odblokowała}} $2.',
	'chat-joined' => '$1 {{GENDER:$2|dołączył|dołączyła}} na czat.',
	'chat-left' => '$1 {{GENDER:$2|opuścił|opuściła}} czat.',
	'chat-private-message' => 'kliknij, aby wysłać prywatną wiadomość',
	'chat-user-is-moderator' => 'Ten użytkownik {{GENDER:$1|jest}} moderatorem.',
	'chat-you-are-moderator' => 'Ty {{GENDER:$1|jesteś}} moderatorem.',
	'chat-topic' => 'Witamy na czacie {{SITENAME}}. ([[Special:Log/chat|rejestr czatu]])',
	'chat-change-preferences' => 'Zmień ustawienia czatu',
	'chat-sidebar-online' => 'Użytkowników online na czacie:',
	'chat-sidebar-join' => 'Dołącz do czatu',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => 'Rejestr czatu',
	'group-chatmod' => 'Moderatorzy czatu',
	'group-chatmod-member' => '{{GENDER:$1|moderator czatu|moderatorka czatu}}',
	'grouppage-chatmod' => '{{ns:project}}:Moderatorzy czatu',
	'right-mediawikichat-chat' => 'Użyj Specjalna:Chat',
	'prefs-chat' => 'Czat',
);

/** Pashto (پښتو)
 * @author Ahmed-Najib-Biabani-Ibrahimkhel
 */
$messages['ps'] = array(
	'chat' => 'بانډار',
	'chat-desc' => 'يو سپک وزنه بانډار پېرن او پالنگر برابروي',
	'chat-type-your-message' => 'خپل پيغام دلته وټاپئ',
	'chat-type-your-private-message' => 'خپل شخصي پيغام دلته وټاپئ',
	'chat-no-other-users' => 'نور کارنان په بانډار کې نشته',
	'chat-blocked-from-chat' => 'په دې بانډار کې په تاسې بنديز لگېدلی',
	'chat-not-allowed' => 'تاسې بانډار نه شی کولی، لومړی غونډال ته ورننوځئ.',
	'chat-just-now' => 'همدا اوس',
	'chat-a-minute-ago' => 'يوه دقيقه دمخه',
	'chat-yesterday' => 'پرون',
	'chat-youve-been-kicked' => 'تاسې د $1 لخوا {{GENDER:$2|وشړل شوئ}}. د بانډار لپاره مو مخ بياتاند کړئ.',
	'chat-you-kicked' => 'تاسې $1 {{GENDER:$2|وشاړه}}.',
	'chat-kicked' => '$2 د $1 لخوا {{GENDER:$3|وشړل شو}}.',
	'chat-kick' => 'شړل',
	'chat-youve-been-blocked' => 'د $1 لخوا پر تاسې {{GENDER:$2|بنديز لگېدلی}}.',
	'chat-you-blocked' => 'تاسې پر $1 {{GENDER:$2|بنديز لگولی}}.',
	'chat-blocked' => '$1 پر $2 {{GENDER:$3|بنديز لگولی}}.',
	'chat-block' => 'بنديز لگول',
	'chat-you-unblocked' => 'تاسې له $1 {{GENDER:$2|بنديز لرې کړ}}.',
	'chat-unblocked' => '$1 له $2 {{GENDER:$3|بنديز لرې کړ}}.',
	'chat-private-message' => '(شخصي پيغام)',
	'chat-user-is-moderator' => 'دا کارن يو منځگړی {{GENDER:$1|دی}}.',
	'chat-you-are-moderator' => 'تاسې يو منځگړی {{GENDER:$1|ياست}}.',
	'chat-joined' => '$1 په بانډار کې {{GENDER:$2|ورگډ شو}}.',
	'chat-left' => '$1 بانډار {{GENDER:$2|پرېښوده}}.',
	'chat-topic' => 'د {{SITENAME}} بانډار ته ښه راغلئ. ([[Special:Log/chat|بانډار ته ورننوتل]])',
	'chat-sidebar-online' => 'په بانډار کې پرليکه کارنان:',
	'chat-sidebar-join' => 'بانډار کې ورگډ شئ',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => 'بانډار يادښت',
	'log-description-chat' => 'پيغامونه د مېډياويکي بانډار لخوا ولېږل شول، همداراز کارن شړل شوی',
	'logentry-chat-kick' => '$4 د $1 لخوا {{GENDER:$1|وشړل شو}}',
	'log-show-hide-chat' => 'بانډار يادښت $1',
	'group-chatmod' => 'بانډار منځگړي',
	'group-chatmod-member' => '{{GENDER:$1|بانډار منځگړی}}',
	'grouppage-chatmod' => '{{ns:project}}:بانډار منځگړي',
	'group-blockedfromchat' => 'په بانډار کې بنديز لگېدلي کارنان',
	'group-blockedfromchat-member' => '{{GENDER:$1|له بانډار څخه بنديز شوی}}',
	'grouppage-blockedfromchat' => '{{ns:project}}:په بانډار کې بنديز لگېدلي کارنان',
	'right-mediawikichat-chat' => 'ځانگړې کارېدنه:بانډار',
	'right-mediawikichat-modchat' => 'له ځانگړي:بانډار څخه کارنان شړي او بنديز پرې لگوي (که چارن شي)',
);

/** Portuguese (português)
 * @author Imperadeiro98
 */
$messages['pt'] = array(
	'chat-user-is-moderator' => 'Este utilizador {{GENDER:$1|é}} um moderador',
);

/** Brazilian Portuguese (português do Brasil)
 * @author Rodrigo codignoli
 */
$messages['pt-br'] = array(
	'chat' => 'Bate-papo',
	'chat-desc' => 'Providencia um chat e servidor leves',
	'chat-type-your-message' => 'Digite sua mensagem',
	'chat-type-your-private-message' => 'Digite sua mensagen privada',
	'chat-no-other-users' => 'Não a outros usuarios no bate-papo',
	'chat-blocked-from-chat' => 'Você foi bloqueado nesse chat.',
	'chat-not-allowed' => 'Você não esta autorizado para entrar no chat, tente efetuar o loggin antes',
	'chat-just-now' => 'agora mesmo',
	'chat-a-minute-ago' => 'um minuto atrás',
	'chat-yesterday' => 'Ontem',
	'chat-youve-been-kicked' => 'Você foi {{GENDER:$2|quicado}} por $1. Recarrege a pagina para voltar ao chat',
	'chat-you-kicked' => 'Você {{GENDER:$2|quicou}} $1',
	'chat-kicked' => '$1{{{GENDER:$3|chutou}}', # Fuzzy
	'chat-kick' => 'Chute',
	'chat-youve-been-blocked' => 'Você foi {{GENDER:$2|bloqueado}} por $1',
	'chat-you-blocked' => 'Você {{GENDER:$2|bloqueou}}$1',
	'chat-blocked' => '$1 {{GENDER:$3|bloqueou}} $2',
	'chat-block' => 'bloquear',
	'chat-you-unblocked' => 'Você {{GENDER:$2|desbloqueo}}$1',
	'chat-unblocked' => '$1{{GENDER:$3|desbloqueou}}$2',
	'chat-private-message' => '(mensagen privada)',
	'chat-user-is-moderator' => 'Esse usuario {{GENDER:$1|is}} é um moderador',
	'chat-you-are-moderator' => 'Você {{GENDER:$1|é}}um moderador',
	'chat-joined' => '$1 {{GENDER:$2|entrou}} no bate-papo',
	'chat-left' => '$1 {{GENDER:$2|deixou}} o bate-papo',
	'chat-topic' => 'Bem vindo ao {{SITENAME}} bate-papo. ([[Special:Log/chat log]])', # Fuzzy
	'chat-sidebar-online' => 'Usuarios do chat que estão online:',
	'chat-sidebar-join' => 'Entrar no site',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => 'log do chat',
	'log-description-chat' => 'Mensagens enviadas por MediaWikiChat, bem como usuarios quicados',
	'logentry-chat-kick' => '$1{{GENDER:$1|quicou}} $4',
	'log-show-hide-chat' => '$1 chat log',
	'group-chatmod' => 'Moderadores do bate-papo',
	'group-chatmod-member' => '{{GENDER:$1|moderador do chat}}',
	'grouppage-chatmod' => '{{ns:project}}: Moderadores de chat',
	'group-blockedfromchat' => 'Usuarios bloqueados do bate-papo',
	'group-blockedfromchat-member' => '{{GENDER:$1|bloqueado no chat}}',
	'grouppage-blockedfromchat' => '{{ns:project}}:Usuarios bloqueados no chat',
	'right-mediawikichat-chat' => 'Use Special:Chat',
	'right-mediawikichat-modchat' => 'Bloquea e quica (se autorizado) usuarios do Special:Chat',
);

/** Quechua (Runa Simi)
 * @author AlimanRuna
 */
$messages['qu'] = array(
	'chat-a-minute-ago' => 'huk minutu ñaqha',
);

/** tarandíne (tarandíne)
 * @author Joetaras
 */
$messages['roa-tara'] = array(
	'chat-you-blocked' => 'Tu è state {{GENDER:$2|bloccate}} $1.',
	'chat-you-unblocked' => 'Tu è state {{GENDER:$2|sbloccate}} $1.',
	'chat-left' => '$1 è {{GENDER:$2|lassate}} a ciat.',
);

/** Russian (русский)
 * @author Okras
 */
$messages['ru'] = array(
	'chat' => 'Чат',
	'chat-desc' => 'Лёгкий чат-клиент с серверной частью',
	'chat-type-your-message' => 'Введите ваше сообщение',
	'chat-type-your-private-message' => 'Введите личное сообщение',
	'chat-no-other-users' => 'Других пользователей в чате нет',
	'chat-blocked-from-chat' => 'Вы были заблокированы в этом чате.',
	'chat-not-allowed' => 'Вы не можете общаться, попробуйте сначала войти в чат',
	'chat-sounds' => 'Проигрывать звуки',
	'chat-flood' => 'Нельзя отправлять сообщения так быстро. Пожалуйста, подождите, прежде чем отправить что-то ещё.',
	'chat-too-long' => 'Это сообщение было слишком длинным, отправьте более короткое сообщение.',
	'chat-just-now' => 'только что',
	'chat-a-minute-ago' => 'минуту назад',
	'chat-yesterday' => 'вчера',
	'chat-kick' => 'выбросить',
	'chat-block' => 'заблокировать',
	'chat-youve-been-kicked' => 'Вы были {{GENDER:$2|выброшены}} участником $1. Обновите страницу чата.',
	'chat-you-kicked' => 'Вы {{GENDER:$2|выбросили}} $1.',
	'chat-kicked' => '$1 выбросил{{GENDER:$3||а}} $2.',
	'chat-youve-been-blocked' => 'Вы были {{GENDER:$2|заблокированы}} участником $1.',
	'chat-you-blocked' => 'Вы {{GENDER:$2|заблокировали}} $1.',
	'chat-blocked' => '$1 заблокировал{{GENDER:$3||а}} $2.',
	'chat-you-unblocked' => 'Вы {{GENDER:$2|разблокировали}} $1.',
	'chat-unblocked' => '$1 разблокировал{{GENDER:$3||а}} $2.',
	'chat-joined' => '$1 {{GENDER:$2|присоединился|присоединилась}} к чату.',
	'chat-left' => '$1 покинул{{GENDER:$2||а}} чат.',
	'chat-private-message' => 'нажмите для отправки личного сообщения',
	'chat-user-is-moderator' => 'Этот пользователь — {{GENDER:$1|модератор}}.',
	'chat-you-are-moderator' => 'Вы — {{GENDER:$1|модератор}}.',
	'chat-topic' => 'Добро пожаловать в чат сайта {{SITENAME}}. ([[Special:Log/chat|журнал чата]])',
	'chat-change-preferences' => 'Изменить настройки чата',
	'chat-sidebar-online' => 'Пользователи в чатеː',
	'chat-sidebar-join' => 'Присоединиться к чату',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => 'Лог чата',
	'log-description-chat' => 'Сообщения, отправляемые MediaWikiChat, когда участник выкидывает другого',
	'logentry-chat-kick' => '$1 выбросил{{GENDER:$1||а}} $4',
	'log-show-hide-chat' => '$1 журнал чата',
	'group-chatmod' => 'Модераторы чата',
	'group-chatmod-member' => '{{GENDER:$1|модератор чата}}',
	'grouppage-chatmod' => '{{ns:project}}:Модераторы чата',
	'group-blockedfromchat' => 'Участники, заблокированные в чате',
	'group-blockedfromchat-member' => 'заблокирован{{GENDER:$1||а}} в чате',
	'grouppage-blockedfromchat' => '{{ns:project}}:Участники, заблокированные в чате',
	'right-mediawikichat-chat' => 'Использовать Special:Chat',
	'right-mediawikichat-modchat' => 'Блокировать и выкидывать (если включено) участников из Special:Chat',
	'tog-chat-ping-mention' => 'Проигрывать звук при появлении упоминаний',
	'tog-chat-ping-pm' => 'Проигрывать звук при получении личных сообщений',
	'tog-chat-ping-message' => 'Проигрывать звук при получении обычных сообщений',
	'prefs-chat' => 'Чат',
);

/** Scots (Scots)
 * @author John Reid
 */
$messages['sco'] = array(
	'chat-sounds' => 'Pley soonds',
	'chat-flood' => 'Ye canna send messages that fast, please wait a while afore sendin oni mair.',
	'chat-too-long' => "That message wis o'er lang, please send ae shorter message. (Or twa).",
	'chat-private-message' => 'clap tae private message',
	'chat-user-is-moderator' => 'This uiser {{GENDER:$1|is}} ae moderator',
	'chat-you-are-moderator' => 'Ye {{GENDER:$1|are}} ae moderator',
	'chat-change-preferences' => 'Chynge yer chat preferences',
	'tog-chat-ping-mention' => 'Pley ae soond on recieving mentions',
	'tog-chat-ping-pm' => 'Pley ae soond on recieving private messages',
	'tog-chat-ping-message' => 'Pley ae soond on recieving ordinair messages',
	'prefs-chat' => 'Chat',
);

/** Sinhala (සිංහල)
 * @author Thushara
 */
$messages['si'] = array(
	'chat-blocked-from-chat' => 'ඔබ මෙම කතිකාවට සහහාගී වීම වලක්වා ඇත.',
);

/** Serbian (Cyrillic script) (српски (ћирилица)‎)
 * @author Rancher
 */
$messages['sr-ec'] = array(
	'chat-sounds' => 'Репродукуј звукове',
	'chat-flood' => 'Не можете да шаљете поруке толико брзо. Сачекајте па пошаљите другу.',
	'chat-change-preferences' => 'Промени поставке ћаскања',
	'tog-chat-ping-mention' => 'Репродукуј звук када вас неко помене',
	'tog-chat-ping-pm' => 'Репродукуј звук по доспећу приватних порука',
	'tog-chat-ping-message' => 'Репродукуј звук по доспећу нормалних порука',
	'prefs-chat' => 'Ћаскање',
);

/** Swedish (svenska)
 * @author Jopparn
 * @author Lokal Profil
 * @author WikiPhoenix
 */
$messages['sv'] = array(
	'chat' => 'Chatt',
	'chat-desc' => 'Tillhandahåller en lätt chattklient och server',
	'chat-type-your-message' => 'Skriv ditt meddelande',
	'chat-type-your-private-message' => 'Skriv ditt privata meddelande',
	'chat-no-other-users' => 'Inga andra användare i chatten',
	'chat-blocked-from-chat' => 'Du har blockerats från denna chatt.',
	'chat-not-allowed' => 'Du är inte tillåten att chatta, försök logga in först.',
	'chat-sounds' => 'Spela upp ljud',
	'chat-too-long' => 'Meddelandet var för lång, skicka ett kortare meddelande.',
	'chat-just-now' => 'just nu',
	'chat-a-minute-ago' => 'en minut sedan',
	'chat-yesterday' => 'i går',
	'chat-kick' => 'sparka',
	'chat-block' => 'blockera',
	'chat-youve-been-kicked' => 'Du har {{GENDER:$2|sparkats ut}} av $1. Uppdatera sidan för att chatta.',
	'chat-you-kicked' => 'Du {{GENDER:$2|sparkade ut}} $1.',
	'chat-kicked' => '$1 {{GENDER:$3|sparkade ut}} $2.',
	'chat-youve-been-blocked' => 'Du har {{GENDER:$2|blockerats}} av $1.',
	'chat-you-blocked' => 'Du {{GENDER:$2|blockerade}} $1.',
	'chat-blocked' => '$1 {{GENDER:$3|blockerade}} $2.',
	'chat-you-unblocked' => 'Du {{GENDER:$2|avblockerade}} $1.',
	'chat-unblocked' => '$1 {{GENDER:$3|avblockerade}} $2.',
	'chat-joined' => '$1 {{GENDER:$2|anslöt sig}} till chatten.',
	'chat-left' => '$1 {{GENDER:$2|lämnade}} chatten.',
	'chat-private-message' => 'klicka för privat meddelande',
	'chat-user-is-moderator' => '{{GENDER:$1|Denna användare}} är en moderator.',
	'chat-you-are-moderator' => '{{GENDER:$1|Du är}} en moderator.',
	'chat-topic' => 'Välkommen till {{SITENAME}}s chatt. ([[Special:Log/chat|chattlogg]])',
	'chat-sidebar-online' => 'Anslutna användare i chatten:',
	'chat-sidebar-join' => 'Delta i chatten',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => 'Chattlogg',
	'logentry-chat-kick' => '$1 {{GENDER:$1|sparkade ut}} $4.',
	'log-show-hide-chat' => '$1 chattlogg',
	'group-chatmod' => 'Chattmoderatorer',
	'group-chatmod-member' => '{{GENDER:$1|chattmoderator}}',
	'grouppage-chatmod' => '{{ns:project}}:Chattmoderatorer',
	'group-blockedfromchat' => 'Användare blockerade från chatten',
	'group-blockedfromchat-member' => '{{GENDER:$1|blockerad från chatten}}',
	'grouppage-blockedfromchat' => '{{ns:project}}:Användare blockerade från chatten',
	'right-mediawikichat-chat' => 'Använd Special:Chat',
	'right-mediawikichat-modchat' => 'Blockera och sparka ut (om det är aktiverat) användare från Special:Chat',
	'tog-chat-ping-pm' => 'Spela upp ett ljud när privata meddelanden tas emot',
	'tog-chat-ping-message' => 'Spela upp ett ljud när normala meddelanden tas emot',
);

/** Telugu (తెలుగు)
 * @author Ashokjayanti
 * @author Ravichandra
 * @author Veeven
 */
$messages['te'] = array(
	'chat-sounds' => 'శబ్ధాన్ని వినిపించు',
	'chat-flood' => 'అంత వేగంగా సందేశాలు పంపడం కుదరదు. దయచేసి మరోటి పంపేముందు వేచిఉండండి.',
	'chat-just-now' => 'ఇప్పుడే',
	'chat-a-minute-ago' => 'ఒక నిమిషం క్రితం',
	'chat-yesterday' => 'నిన్న',
	'chat-private-message' => 'అంతరంగిక సందేశం పంపడానికి నొక్కండి',
	'chat-user-is-moderator' => 'ఈ వాడుకరు పరిశీలకులు', # Fuzzy
	'chat-you-are-moderator' => 'మీరు పరిశీలకులు', # Fuzzy
	'chat-change-preferences' => 'మీ కబుర్ల ప్రాధాన్యతలని మార్చండి',
	'tog-chat-ping-pm' => 'వ్యక్తిగత సందేశాలు వచ్చినప్పుడు శబ్దం వినిపించండి.',
	'tog-chat-ping-message' => 'సాధారణ సందేశాలు వచ్చినప్పుడు శబ్దం వినిపించండి.',
	'prefs-chat' => 'మాటామంతి',
);

/** Turkish (Türkçe)
 * @author Joseph
 */
$messages['tr'] = array(
	'chat' => 'Sohbet',
	'chat-desc' => 'Hafif bir sohbet istemcisi ve sunucusu sağlar',
	'chat-type-your-message' => 'Mesajınızı yazın',
	'chat-type-your-private-message' => 'Özel mesajınızı yazın',
	'chat-no-other-users' => 'Sohbette başka kullanıcı yok',
	'chat-blocked-from-chat' => 'Bu sohbette engellendiniz.',
	'chat-not-allowed' => 'Sohbete izniniz yok, önce giriş yapmayı deneyin',
	'chat-just-now' => 'hemen şimdi',
	'chat-a-minute-ago' => 'bir dakika önce',
	'chat-yesterday' => 'dün',
	'chat-youve-been-kicked' => '$1 tarafından {{GENDER:$2|atıldınız}}. Sohbet için sayfayı yenileyin',
	'chat-you-kicked' => '$1 kullanıcısını {{GENDER:$2|attınız}}',
	'chat-kicked' => '$1, $2 kullanıcısını {{GENDER:$3|attı}}',
	'chat-kick' => 'at',
	'chat-youve-been-blocked' => '$1 tarafından {{GENDER:$2|engellendiniz}}.',
	'chat-you-blocked' => '$1 kullanıcısını {{GENDER:$2|engellediniz}}',
	'chat-blocked' => '$2, $1 kullanıcısını {{GENDER:$3|engelledi}}',
	'chat-block' => 'engelle',
	'chat-you-unblocked' => '$1 kullanıcısının {{GENDER:$2|engelini kaldırdınız}}',
	'chat-unblocked' => '$1, $2 kullanıcısının {{GENDER:$3|engelini kaldırdı}}',
	'chat-private-message' => '(özel mesaj)',
	'chat-user-is-moderator' => 'Bu kullanıcı {{GENDER:$1|bir}} moderatör',
	'chat-you-are-moderator' => 'Siz {{GENDER:$1|bir}} moderatörsünüz',
	'chat-joined' => '$1 sohbete {{GENDER:$2|katıldı}}',
	'chat-left' => '$1 sohbetten {{GENDER:$2|ayrıldı}}',
	'chat-topic' => '{{SITENAME}} sohbete hoş geldiniz. ([[Special:Log/chat|sohbet günlüğü]])',
	'chat-sidebar-online' => 'Sohbetteki çevrimiçi kullanıcılar:',
	'chat-sidebar-join' => 'Sohbete katıl',
	'log-name-chat' => 'Sohbet günlüğü',
	'logentry-chat-kick' => '$1, $4 kullanıcısını {{GENDER:$1|attı}}',
	'log-show-hide-chat' => 'Sohbet günlüğünü $1',
	'group-chatmod' => 'Sohbet moderatörleri',
	'group-chatmod-member' => '{{GENDER:$1|sohbet moderatörü}}',
	'grouppage-chatmod' => '{{ns:project}}:Sohbet moderatörleri',
	'group-blockedfromchat' => 'Sohbette engellenen kullanıcılar',
	'group-blockedfromchat-member' => '{{GENDER:$1|sohbette engellenen}}',
	'grouppage-blockedfromchat' => '{{ns:project}}:Sohbette engellenen kullanıcılar',
	'right-mediawikichat-chat' => 'Special:Chat kullan',
	'right-mediawikichat-modchat' => "Special:Chat'te kullanıcıları engelle ve at (etkinleştirildiyse)",
);

/** Ukrainian (українська)
 * @author Andriykopanytsia
 */
$messages['uk'] = array(
	'chat' => 'Чат',
	'chat-desc' => 'Забезпечує легкий клієнт чату і сервер',
	'chat-type-your-message' => 'Введіть ваше повідомлення',
	'chat-type-your-private-message' => 'Введіть ваше особисте повідомлення',
	'chat-no-other-users' => 'Немає інших користувачів у чаті',
	'chat-blocked-from-chat' => 'Ви були заблоковані у цьому чаті.',
	'chat-not-allowed' => 'Вам не дозволено спілкуватися, спробуйте спершу увійти.',
	'chat-sounds' => 'Відтворити звуки',
	'chat-flood' => 'Не вдалося надіслати повідомлення так швидко. Будь ласка, почекайте, перш ніж надсилати більше.',
	'chat-too-long' => 'Це повідомлення було занадто довгим, будь ласка, надішліть короткі повідомлення.',
	'chat-just-now' => 'щойно',
	'chat-a-minute-ago' => 'хвилину тому',
	'chat-yesterday' => 'вчора',
	'chat-kick' => 'викинути',
	'chat-block' => 'заблокувати',
	'chat-youve-been-kicked' => 'Ви були {{GENDER:$2|викинути}} учасником $1. Оновіть сторінку чату.',
	'chat-you-kicked' => 'Ви {{GENDER:$2|викинули}} $1.',
	'chat-kicked' => '$1 {{GENDER:$3|викинув|викинула}} $2',
	'chat-youve-been-blocked' => 'Ви вже були {{GENDER:$2|заблоковані}} учасником $1.',
	'chat-you-blocked' => 'Ви {{GENDER:$2|заблокували}} $1.',
	'chat-blocked' => '$1 {{GENDER:$3|заблокував|заблокувала}}  $2.',
	'chat-you-unblocked' => 'Ви {{GENDER:$2|розблокували}} $1.',
	'chat-unblocked' => '$1 {{GENDER:$3|розблокував|розблокувала}}  $2.',
	'chat-joined' => '$1 {{GENDER:$2|приєднався|приєдналася}} до чату.',
	'chat-left' => '$1 {{GENDER:$2|залишив|залишила}} чат.',
	'chat-private-message' => 'клацніть для особистого повідомлення',
	'chat-user-is-moderator' => '{{GENDER:$1|Цей користувач - модератор|Ця користувачка - модераторка}}.',
	'chat-you-are-moderator' => 'Ви — {{GENDER:$1|модератор|модераторка}}.',
	'chat-topic' => 'Ласкаво просимо до чату сайту {{SITENAME}}. ([[Special:Log/chat|журнал чату]])',
	'chat-change-preferences' => 'Змінити налаштування чату',
	'chat-sidebar-online' => 'Онлайн-користувачів у чаті:',
	'chat-sidebar-join' => 'Приєднатися до чату',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => 'Журнал чату',
	'log-description-chat' => 'Повідомлення від MediaWikiChat, коли користувач усуває іншого',
	'logentry-chat-kick' => '$1 {{GENDER:$1|усунув|усунула}} $4',
	'log-show-hide-chat' => '$1 журнал чату',
	'group-chatmod' => 'Модератори чату',
	'group-chatmod-member' => '{{GENDER:$1|модератор чату|модераторка чату}}',
	'grouppage-chatmod' => '{{ns:project}}:модератори чату',
	'group-blockedfromchat' => 'Заблоковані у чаті користувачі',
	'group-blockedfromchat-member' => '{{GENDER:$1|заблокований у чаті|заблокована у чаті}}',
	'grouppage-blockedfromchat' => '{{ns:project}}: заблоковані у чаті користувачі',
	'right-mediawikichat-chat' => 'Використовувати Special:Chat',
	'right-mediawikichat-modchat' => 'Блокувати та усувати (коли увімкнено) користувачів із Special:Chat',
	'tog-chat-ping-mention' => 'Подавати звуковий сигнал при одержанні згадки',
	'tog-chat-ping-pm' => 'Подавати звуковий сигнал при одержанні приватних повідомлень',
	'tog-chat-ping-message' => 'Подавати звуковий сигнал при отриманні нормальних повідомлень',
	'prefs-chat' => 'Чат',
);

/** Vietnamese (Tiếng Việt)
 * @author Minh Nguyen
 * @author Skye Darcy
 * @author Withoutaname
 */
$messages['vi'] = array(
	'chat-type-your-message' => 'Nhập tin nhắn của bạn',
	'chat-type-your-private-message' => 'Nhập tin nhắn riêng của bạn',
	'chat-no-other-users' => 'Không có người dùng khác trong phòng trò chuyện',
	'chat-blocked-from-chat' => 'Bạn đã bị cấm không được vào phòng trò chuyện này.',
	'chat-flood' => 'Bạn không thể gửi tin nhắn quá nhanh, xin vui lòng đợi trước khi gửi thêm.',
	'chat-too-long' => 'Tin nhắn đó là quá dài, xin vui lòng gửi một tin nhắn ngắn hơn.',
	'chat-just-now' => 'mới bây giờ',
	'chat-a-minute-ago' => 'cách đây một phút',
	'chat-yesterday' => 'hôm qua',
	'chat-kick' => 'đá ra',
	'chat-block' => 'cấm',
	'chat-you-kicked' => 'Bạn {{GENDER:$2}}đá ra $1.',
	'chat-kicked' => '$1 {{GENDER:$3}}đá ra $2.',
	'chat-youve-been-blocked' => '$1 {{GENDER:$2}}cấm bạn.',
	'chat-you-blocked' => 'Bạn {{GENDER:$2}}cấm $1.',
	'chat-blocked' => '$1 {{GENDER:$3}}cấm $2.',
	'chat-you-unblocked' => 'Bạn {{GENDER:$2}}bỏ cấm $1.',
	'chat-unblocked' => '$1 {{GENDER:$3}}bỏ cấm $2.',
	'chat-joined' => '$1 {{GENDER:$2}}đi vào phòng trò chuyện.',
	'chat-left' => '$1 {{GENDER:$2}}bỏ phòng trò chuyện.',
	'chat-private-message' => '(tin nhắn riêng)', # Fuzzy
	'chat-user-is-moderator' => 'Thành viên này {{GENDER:$1}}là một quản trị viên.',
	'chat-you-are-moderator' => 'Bạn {{GENDER:$1}}là quản trị viên.',
	'chat-sidebar-join' => 'Vào phòng trò chuyện',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'logentry-chat-kick' => '$1 {{GENDER:$1}}đá ra $4.',
	'group-chatmod' => 'Quản trị viên của phòng trò chuyện',
	'group-chatmod-member' => '{{GENDER:$1}}quản trị viên của phòng trò chuyện',
	'grouppage-chatmod' => '{{ns:project}}:Quản trị viên của phòng trò chuyện',
	'group-blockedfromchat' => 'Người dùng bị cấm không được trò chuyện',
	'group-blockedfromchat-member' => '{{GENDER:$1}}bị cấm không được trò chuyện',
	'grouppage-blockedfromchat' => '{{ns:project}}:Người dùng bị cấm không được trò chuyện',
);

/** Yiddish (ייִדיש)
 * @author פוילישער
 */
$messages['yi'] = array(
	'chat-a-minute-ago' => 'פאר איין מינוט',
	'chat-minutes-ago' => 'פאר {{PLURAL:$1|1 מינוט|$1 מינוט}}',
	'chat-yesterday' => 'נעכטן',
);

/** Simplified Chinese (中文（简体）‎)
 * @author Liuxinyu970226
 * @author Qiyue2001
 * @author Yfdyh000
 */
$messages['zh-hans'] = array(
	'chat' => '聊天',
	'chat-desc' => '提供一个轻量级的聊天客户端和服务器',
	'chat-type-your-message' => '键入您的消息',
	'chat-type-your-private-message' => '键入您的私人信息',
	'chat-no-other-users' => '没有其他用户在线',
	'chat-blocked-from-chat' => '您已经被此聊天封禁。',
	'chat-not-allowed' => '您不被允许聊天，请先尝试登录。',
	'chat-just-now' => '刚刚',
	'chat-a-minute-ago' => '一分钟前',
	'chat-yesterday' => '昨天',
	'chat-kick' => '踢',
	'chat-block' => '封禁',
	'chat-youve-been-kicked' => '你已经被$1{{GENDER:$2|踢出}}。刷新页面以聊天',
	'chat-you-kicked' => '您{{GENDER:$2|踢了}}$1',
	'chat-kicked' => '$1{{GENDER:$3|踢了}}$2',
	'chat-youve-been-blocked' => '你已经被$1{{GENDER:$2|封禁}}聊天。',
	'chat-you-blocked' => '您{{GENDER:$2|封禁了}}$1',
	'chat-blocked' => '$1{{GENDER:$3|封禁了}}$2',
	'chat-you-unblocked' => '您{{GENDER:$2|解禁}}了$1',
	'chat-unblocked' => '$1{{GENDER:$3|解禁}}了$2',
	'chat-joined' => '$1{{GENDER:$2|加入}}了聊天',
	'chat-left' => '$1{{GENDER:$2|离开}}了聊天',
	'chat-private-message' => '点击到私人消息',
	'chat-user-is-moderator' => '此用户{{GENDER:$1|是}}一位版主',
	'chat-you-are-moderator' => '您{{GENDER:$1|是}}一位版主',
	'chat-topic' => '欢迎来到{{SITENAME}}聊天。（[[Special:Log/chat|聊天日志]]）',
	'chat-sidebar-online' => '聊天中的在线用户：',
	'chat-sidebar-join' => '加入聊天',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => '聊天记录',
	'log-description-chat' => '信息由MediaWikiChat发送，当然用户可以踢人',
	'logentry-chat-kick' => '$1踢出了$4',
	'log-show-hide-chat' => '$1聊天记录',
	'group-chatmod' => '聊天版主',
	'group-chatmod-member' => '{{GENDER:$1|聊天版主}}',
	'grouppage-chatmod' => '{{ns:project}}:聊天版主',
	'group-blockedfromchat' => '被阻止聊天的用户',
	'group-blockedfromchat-member' => '{{GENDER:$1|从聊天被封禁者}}',
	'grouppage-blockedfromchat' => '{{ns:project}}:从聊天中被封禁的用户',
	'right-mediawikichat-chat' => '使用Special:Chat',
	'right-mediawikichat-modchat' => '从Special:Chat将用户封禁和踢人（如果可用）',
	'prefs-chat' => '聊天',
);

/** Traditional Chinese (中文（繁體）‎)
 * @author EagerLin
 * @author Liuxinyu970226
 */
$messages['zh-hant'] = array(
	'chat' => '聊天',
	'chat-desc' => '提供了一個羽量級的聊天用戶端和伺服器',
	'chat-type-your-message' => '鍵入您的消息',
	'chat-type-your-private-message' => '鍵入您的私人資訊',
	'chat-no-other-users' => '沒有其他使用者在線',
	'chat-blocked-from-chat' => '您已被封禁，不能聊天。',
	'chat-not-allowed' => '您不允許在聊天、請優先嘗試登錄。',
	'chat-just-now' => '剛才',
	'chat-a-minute-ago' => '一分鐘前',
	'chat-yesterday' => '昨天',
	'chat-youve-been-kicked' => '你已被$1{{GENDER:$2|請出}}。刷新頁面聊天。',
	'chat-you-kicked' => '您{{GENDER:$2|請出了}}$1',
	'chat-kicked' => '$1{{GENDER:$3|請出了}}$2',
	'chat-kick' => '踢',
	'chat-youve-been-blocked' => '你已經被$1{{GENDER:$2|查封}}聊天。',
	'chat-you-blocked' => '您{{GENDER:$2|封禁了}}$1',
	'chat-blocked' => '$1{{GENDER:$3|封禁了}}$2',
	'chat-block' => '查封',
	'chat-you-unblocked' => '您{{GENDER:$2|解禁}}了$1',
	'chat-unblocked' => '$1{{GENDER:$3|解禁}}了$2',
	'chat-private-message' => '(悄悄話)',
	'chat-user-is-moderator' => '此使用者{{GENDER:$1|是}}版主。',
	'chat-you-are-moderator' => '您{{GENDER:$1|是}}版主。',
	'chat-joined' => '$1{{GENDER:$2|加入}}聊天。',
	'chat-left' => '$1{{GENDER:$2|離開}}了聊天',
	'chat-topic' => '歡迎來到{{SITENAME}}聊天。（[[Special:Log/chat|聊天日誌]]）',
	'chat-sidebar-online' => '在聊天中的線上使用者：',
	'chat-sidebar-join' => '加入聊天',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => '聊天記錄',
	'log-description-chat' => '信息由MediaWikiChat發送，當然使用者可以將別人請出',
	'logentry-chat-kick' => '$1請出了$4',
	'log-show-hide-chat' => '$1聊天記錄',
	'group-chatmod' => '聊天版主',
	'group-chatmod-member' => '{{GENDER:$1|聊天版主}}',
	'grouppage-chatmod' => '{{ns:project}}：聊天版主', # Fuzzy
	'group-blockedfromchat' => '被阻止聊天的用户',
	'group-blockedfromchat-member' => '{{GENDER:$1|於聊天中封禁者}}',
	'grouppage-blockedfromchat' => '{{ns:project}}:從聊天中被封禁的用戶',
	'right-mediawikichat-chat' => '使用Special:Chat',
);
