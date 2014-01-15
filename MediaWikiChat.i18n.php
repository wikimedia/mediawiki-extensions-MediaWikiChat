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
	'chat-not-allowed' => 'You are not allowed to chat, try logging in first',

	'chat-just-now' => 'just now',

	'chat-a-minute-ago' => 'a minute ago',
	'chat-minutes-ago' => '{{PLURAL:$1|1 minute|$1 minutes}} ago',

	'chat-yesterday' => 'yesterday',

	'chat-youve-been-kicked' => 'You have been {{GENDER:$2|kicked}} by $1. Refresh the page to chat',
	'chat-you-kicked' => 'You {{GENDER:$2|kicked}} $1',
	'chat-kicked' => '$1 {{GENDER:$3|kicked}} $2',
	'chat-kick' => 'kick',

	'chat-youve-been-blocked' => 'You have been {{GENDER:$2|blocked}} by $1.',
	'chat-you-blocked' => 'You {{GENDER:$2|blocked}} $1',
	'chat-blocked' => '$1 {{GENDER:$3|blocked}} $2',
	'chat-block' => 'block',

	'chat-you-unblocked' => 'You {{GENDER:$2|unblocked}} $1',
	'chat-unblocked' => '$1 {{GENDER:$3|unblocked}} $2',

	'chat-private-message' => '(private message)',
	'chat-user-is-moderator' => 'This user {{GENDER:$1|is}} a moderator',
	'chat-you-are-moderator' => 'You {{GENDER:$1|are}} a moderator',
	'chat-joined' => '$1 has {{GENDER:$2|joined}} the chat',
	'chat-left' => '$1 has {{GENDER:$2|left}} chat',

	'chat-topic' => 'Welcome to {{SITENAME}} chat. ([[Special:Log/chat|chat log]])',

	'chat-sidebar-online' => 'Online users in chat:',
	'chat-sidebar-join' => 'Join the chat',

	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png', // Ignore for translation
	'chat-smileys' => '', // Ignore for translation

	'log-name-chat' => 'Chat log',
	'log-description-chat' => 'Messages sent by MediaWikiChat, as well as user kicks',
	'logentry-chat-send' => '$1: $4', // Optional for translation
	'logentry-chat-kick' => '$1 {{GENDER:$1|kicked}} $4',
	'log-show-hide-chat' => '$1 chat log',

	'group-chatmod' => 'Chat moderators',
	'group-chatmod-member' => '{{GENDER:$1|chat moderator}}',
	'grouppage-chatmod' => '{{ns:project}}:Chat moderators',

	'group-blockedfromchat' => 'Users blocked from chat',
	'group-blockedfromchat-member' => '{{GENDER:$1|blocked from chat}}',
	'grouppage-blockedfromchat' => '{{ns:project}}:Users blocked from chat',

	'right-mediawikichat-chat' => 'Use Special:Chat',
	'right-mediawikichat-modchat' => 'Block and kick (if enabled) users from Special:Chat',
);

/** Message documentation (Message documentation)
 * @author Adam Carter
 */
$messages['qqq'] = array(
	'chat' => 'Important! This is the string that appears on Special:SpecialPages',
	'chat-desc' => '{{desc}}',
	'chat-type-your-message' => 'Message shown in the input field you type your message in.',
	'chat-type-your-private-message' => 'The same as chat-type-your-message, but for private messages, shown in the input field for typing private messages',
	'chat-no-other-users' => 'Shown in the user list when there are no other users on chat',
	'chat-blocked-from-chat' => 'Shown to users who have been blocked from chat',
	'chat-not-allowed' => 'Shown to users who do not have sufficient permissions to chat (normally users who are not logged in)',
	'chat-just-now' => 'Timestamps: shown when a message was sent in the last 30 seconds',
	'chat-minutes-ago' => 'Timestamps: the message was sent $1 minutes ago',
	'chat-yesterday' => 'Timestamps: the message was sent yesterday',
	'chat-youve-been-kicked' => 'Shown to users who have been kicked from the chat, $1 being the user who kicked them',
	'chat-you-kicked' => 'Shown when the current user kicked the user $1',
	'chat-kicked' => 'Shown when the user $1 kicked the user $2',
	'chat-youve-been-blocked' => 'Shown when the current user has been blocked, by the user $1',
	'chat-you-blocked' => 'Shown when the current user blocked the user $1',
	'chat-blocked' => 'Shown when the user $1 blocked the user $2',
	'chat-block' => 'The link shown to chatmods to block a user',
	'chat-private-message' => 'The link shown to users to private message another user',
	'chat-you-are-moderator' => 'Shown when the current user is a moderator',
	'chat-joined' => 'Shown when the user $1 joined the chat',
	'chat-left' => 'Shown when the user $1 left the chat',
	'chat-topic' => 'Header shown at the top of Special:Chat, to allow links and policies to be displayed, like you find on many IRC clients',
	'chat-sidebar-online' => 'The header for the chat unit on the sidebar that shows online users on chat',
	'chat-sidebar-join' => 'A link in the sidebar, below the currently active users, linking to Special:Chat',
	'chat-mod-image' => 'The URL of the image to show a user is a moderator',
	'log-name-chat' => 'The name of the chat log',
	'logentry-chat-send' => 'The user $1 sent the message $4',
	'logentry-chat-kick' => 'The user $1 kicked the user $4',
	'log-show-hide-chat' => 'For [[Special:Log]]. Parameters: $1 - {{int:show}} or {{int:hide}}',
	'group-chatmod' => '{{doc-group|chatmod}}',
	'group-chatmod-member' => '{{doc-group|forumadmin|member}}',
	'grouppage-chatmod' => '{{doc-group|forumadmin|page}}',
	'group-blockedfromchat' => '{{doc-group|blockedfromchat}}',
	'group-blockedfromchat-member' => '{{doc-group|blockedfromchat|member}}',
	'grouppage-blockedfromchat' => '{{doc-group|blockedfromchat|page}}',
	'right-mediawikichat-chat' => '{{doc-right|mediawikichat-chat}}',
	'right-mediawikichat-modchat' => '{{doc-right|mediawikichat-modchat}}',
);

/** German (Deutsch)
 * @author George Barnick
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
	'chat-just-now' => 'gerade eben',
	'chat-a-minute-ago' => 'vor einer Minute',
	'chat-minutes-ago' => 'vor {{PLURAL:$1|einer Minute|$1 Minuten}}',
	'chat-yesterday' => 'gestern',
	'chat-youve-been-kicked' => 'Du wurdest von $1 {{GENDER:$2|hinausgeworfen}}. Lade zum Chatten die Seite erneut.',
	'chat-you-kicked' => 'Du hast $1 {{GENDER:$2|hinausgeworfen}}',
	'chat-kicked' => '$1 hat $2 {{GENDER:$3|hinausgeworfen}}',
	'chat-kick' => 'hinauswerfen',
	'chat-youve-been-blocked' => 'Du wurdest von $1 {{GENDER:$2|gesperrt}}.',
	'chat-you-blocked' => 'Du hast $1 {{GENDER:$2|gesperrt}}',
	'chat-blocked' => '$1 {{GENDER:$3|sperrte}} $2',
	'chat-block' => 'sperren',
	'chat-you-unblocked' => 'Du hast $1 {{GENDER:$2|entsperrt}}',
	'chat-unblocked' => '$1 hat $2 {{GENDER:$3|freigegeben}}',
	'chat-private-message' => '(private Nachricht)',
	'chat-user-is-moderator' => '{{GENDER:$1|Dieser Benutzer ist ein Moderator|Diese Benutzerin ist eine Moderatorin}}',
	'chat-you-are-moderator' => 'Du bist {{GENDER:$1|ein Moderator|eine Moderatorin}}',
	'chat-joined' => '$1 ist dem Chat {{GENDER:$2|beigetreten}}',
	'chat-left' => '$1 hat den Chat {{GENDER:$2|verlassen}}',
	'chat-topic' => 'Willkommen im {{SITENAME}}-Chat. ([[Special:Log/chat|Chat-Logbuch]])',
	'chat-sidebar-online' => 'Onlinebenutzer im Chat:',
	'chat-sidebar-join' => 'Dem Chat beitreten',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => 'Chat-Logbuch',
	'log-description-chat' => 'Dieses Logbuch protokolliert von MediaWikiChat versandte Nachrichten und Hinauswerfungen von Benutzern.',
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
);

/** Persian (فارسی)
 * @author Armin1392
 */
$messages['fa'] = array(
	'chat' => 'گفتگو',
	'chat-type-your-message' => 'پیام خود را تایپ کنید',
	'chat-type-your-private-message' => 'پیام خصوصی خود را تایپ کنید',
	'chat-no-other-users' => 'کاربران دیگری در گفتگو نیستند',
	'chat-blocked-from-chat' => 'شما از این گفتگو مسدود شده‌اید.',
	'chat-not-allowed' => 'شما مجاز به گفتگو نیستید، ابتدا سعی کنید وارد شوید',
	'chat-just-now' => 'هم‌اکنون',
	'chat-a-minute-ago' => 'یک دقیقه پیش',
	'chat-minutes-ago' => '{{PLURAL:$1|یک دقیقه|$1 دقیقه}} پیش',
	'chat-yesterday' => 'دیروز',
	'chat-youve-been-kicked' => 'شما توسط $1 {{GENDER:$2|ضربه}} زده‌اید. صفحه را برای گفتگو تجدید کنید',
	'chat-you-kicked' => 'شما $1 را {{GENDER:$2|ضربه زدید}}',
	'chat-kicked' => '$1 {{GENDER:$3|ضربه زدید}} $2',
	'chat-kick' => 'ضربه زدن',
	'chat-youve-been-blocked' => 'شما توسط $1 {{GENDER:$2|مسدود شده‌اید}}.',
	'chat-you-blocked' => 'شما {{GENDER:$2|مسدود شدید}} $1',
	'chat-blocked' => '$1 {{GENDER:$3|مسدود شده}} $2',
	'chat-block' => 'مسدود',
	'chat-you-unblocked' => 'شما {{GENDER:$2|مسدود نشدید}} $1',
	'chat-unblocked' => '$1 {{GENDER:$3|مسدود نشده}} $2',
	'chat-private-message' => '(پیام خصوصی)',
	'chat-user-is-moderator' => 'این کاربر یک مدیر {{GENDER:$1|هست}}',
	'chat-you-are-moderator' => 'شما یک مدیر {{GENDER:$1|هستید}}',
	'chat-joined' => '$1 به گفتگو {{GENDER:$2|پیوسته شده‌است}}',
	'chat-left' => '$1 گفتگو {{GENDER:$2|ترک کرده‌است}}',
	'chat-topic' => 'به گفتگوی {{SITENAME}} خوش آمدید. ([[ویژه:ورود/گفتگو|ورود به گفتگو]])', # Fuzzy
	'chat-sidebar-online' => 'کاربران آنلاین در گفتگو:',
	'chat-sidebar-join' => 'پیوستن به گفتگو',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => 'ورود به گفتگو',
	'logentry-chat-kick' => '$1 {{GENDER:$1|ضربه زده}} $4',
	'log-show-hide-chat' => '$1 ورود به گفتگو',
	'group-chatmod' => 'مدیران گفتگو',
	'group-chatmod-member' => '{{GENDER:$1|مدیر گفتگو}}',
	'grouppage-chatmod' => '{{ns:project}}:مدیران گفتگو',
	'group-blockedfromchat' => 'کاربران مسدود شده از گفتگو',
	'group-blockedfromchat-member' => '{{GENDER:$1|از گفتگو مسدود شده}}',
	'grouppage-blockedfromchat' => '{{ns:project}}:کاربران مسدود شده از گفتگو',
	'right-mediawikichat-chat' => 'استفادهٔ‌خاص:‌ گفتگو',
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
 */
$messages['fr'] = array(
	'chat' => 'Discussion',
	'chat-desc' => 'Fournit un client et un serveur de discussion légers',
	'chat-type-your-message' => 'Tapez votre message',
	'chat-type-your-private-message' => 'Tapez votre message privé',
	'chat-no-other-users' => 'Aucun autre utilisateur sur la discussion',
	'chat-blocked-from-chat' => 'Vous avez été bloqué depuis cette discussion.',
	'chat-not-allowed' => 'Vous n’êtes pas autorisé à discuter, essayez d’abord de vous connecter',
	'chat-just-now' => 'à l’instant',
	'chat-a-minute-ago' => 'il y a une minute',
	'chat-minutes-ago' => 'il y a {{PLURAL:$1|1 minute|$1 minutes}}',
	'chat-yesterday' => 'hier',
	'chat-youve-been-kicked' => 'Vous avez été {{GENDER:$2|éjecté|éjectée}} par $1. Rafraîchissez la page pour discuter',
	'chat-you-kicked' => 'Vous {{GENDER:$2|avez éjecté}} $1',
	'chat-kicked' => '$1 {{GENDER:$3|a éjecté}} $2',
	'chat-kick' => 'éjecter',
	'chat-youve-been-blocked' => 'Vous avez été {{GENDER:$2|bloqué|bloquée}} par $1.',
	'chat-you-blocked' => 'Vous {{GENDER:$2|avez bloqué}} $1',
	'chat-blocked' => '$1 {{GENDER:$3|a bloqué}} $2',
	'chat-block' => 'bloquer',
	'chat-you-unblocked' => 'Vous {{GENDER:$2|avez débloqué}} $1',
	'chat-unblocked' => '$1 {{GENDER:$3|a débloqué}} $2',
	'chat-private-message' => '(message privé)',
	'chat-user-is-moderator' => 'Cet utilisateur {{GENDER:$1|est}} un modérateur',
	'chat-you-are-moderator' => 'Vous {{GENDER:$1|êtes}} un modérateur',
	'chat-joined' => '$1 a {{GENDER:$2|rejoint}} la discussion',
	'chat-left' => '$1 a {{GENDER:$2|quitté}} la discussion',
	'chat-topic' => 'Bienvenue sur la discussion de {{SITENAME}}. ([[Special:Log/chat|journal de discussion]])',
	'chat-sidebar-online' => 'Utilisateurs connectés sur la discussion :',
	'chat-sidebar-join' => 'Rejoindre la discussion',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => 'Journal de discussion',
	'log-description-chat' => 'Messages envoyés par MediaWikiChat, ainsi que les éjections d’utilisateur',
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
);

/** Luxembourgish (Lëtzebuergesch)
 * @author Robby
 */
$messages['lb'] = array(
	'chat' => 'Chat',
	'chat-type-your-message' => 'Tippt Äre Message',
	'chat-no-other-users' => 'Keng aner Benotzer am Chat',
	'chat-blocked-from-chat' => 'Dir gouft an dësem Chat gespaart.',
	'chat-just-now' => 'grad elo',
	'chat-minutes-ago' => '{{PLURAL:$1|virun enger Minutt|viru(n) $1 Minutten}}',
	'chat-yesterday' => 'gëschter',
	'chat-block' => 'spären',
	'chat-private-message' => '(private Message)',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
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
	'chat-minutes-ago' => '{{PLURAL:$1|1 menit|$1 menit}} kepungkur',
	'chat-kicked' => '$1 {{GENDER:$3|ditendang}} $2',
	'chat-kick' => 'tendang',
	'chat-user-is-moderator' => 'Pangganggo kiye {{GENDER:$1|kuwe}} moderator',
	'chat-topic' => 'Sugeng rawuh nang dopokan {{SITENAME}}. ([[Special:Log/chat|log dopokan]])',
	'log-name-chat' => 'Log dopokan',
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
	'chat-just-now' => 'штотуку',
	'chat-a-minute-ago' => 'пред една минута',
	'chat-minutes-ago' => 'пред {{PLURAL:$1|една минута|$1 минути}}',
	'chat-yesterday' => 'вчера',
	'chat-youve-been-kicked' => '$1 ве {{GENDER:$2|исфрли}}. Превчитајте ја страницата за да продолжите со разговорот',
	'chat-you-kicked' => 'Го {{GENDER:$2|исфрливте}} учесникот $1',
	'chat-kicked' => '$1 го {{GENDER:$3|исфрли}} учесникот $2',
	'chat-kick' => 'исфрли',
	'chat-youve-been-blocked' => '$1 ве {{GENDER:$2|блокираше}}.',
	'chat-you-blocked' => 'Го {{GENDER:$2|блокиравте}} корисникот $1',
	'chat-blocked' => '$1 го {{GENDER:$3|блокираше}} корисникот $2',
	'chat-block' => 'блокирај',
	'chat-you-unblocked' => 'Го {{GENDER:$2|одблокиравте}} учесникот $1',
	'chat-unblocked' => '$1 го {{GENDER:$3|одблокира}} учесникот $2',
	'chat-private-message' => '(приватна порака)',
	'chat-user-is-moderator' => 'Корисников {{GENDER:$1|е}} модератор',
	'chat-you-are-moderator' => 'Вие {{GENDER:$1|сте}} модератор',
	'chat-joined' => '$1 се {{GENDER:$2|приклучи}} на разговорот',
	'chat-left' => '$1 го {{GENDER:$2|напушти}} разговорот',
	'chat-topic' => 'Добредојдовте на разговорот во живо на {{SITENAME}}. ([[Special:Log/chat|записник на разговорот]])',
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
);

/** Marathi (मराठी)
 * @author V.narsikar
 */
$messages['mr'] = array(
	'chat' => 'गपागोष्टी',
	'chat-desc' => 'कमी क्षमतेचा चॅट क्लायंट व सर्व्हर उपलब्ध करतो',
	'chat-type-your-message' => 'आपला संदेश टंका',
	'chat-type-your-private-message' => 'आपला खाजगी संदेश टंका',
	'chat-no-other-users' => 'चॅटसाठी इतर कोणी सदस्य नाही',
	'chat-blocked-from-chat' => 'आपण या चॅट करण्यापासून रोखल्या गेले आहात.',
	'chat-not-allowed' => 'आपणास चॅट करण्याची परवानगी नाही,सनोंद प्रवेशाचा प्रयत्न करा',
	'chat-just-now' => 'आत्ताच',
	'chat-a-minute-ago' => 'एका मिनीटापूर्वी',
	'chat-minutes-ago' => '{{PLURAL:$1|एक मिनीटा|$1 मिनीटां}} पूर्वी',
	'chat-yesterday' => 'काल',
	'chat-youve-been-kicked' => '$1 द्वारे आपण {{GENDER:$2|लाथाडल्या गेले आहात}}.
चॅट करण्यास या पानास तरोताजे करा',
	'chat-you-kicked' => 'आपण $1 ला {{GENDER:$3|लाथाडले आहे}}', # Fuzzy
	'chat-kicked' => '$1 ने $2 ला {{GENDER:$3|लाथाडले}}',
	'chat-kick' => 'लाथाडा',
	'chat-youve-been-blocked' => 'आपण $1 द्वारे{{GENDER:$2|प्रतिबंधित आहात}}.',
	'chat-you-blocked' => 'आपण $1 ला {{GENDER:$2|प्रतिबंधित केले आहे}}',
	'chat-blocked' => '$1 ने $2 ला {{GENDER:$3|प्रतिबंधित केले आहे}}',
	'chat-block' => 'प्रतिबंधित करा',
	'chat-you-unblocked' => 'आपण $1 ला {{GENDER:$2|अप्रतिबंधित केले आहे}}',
	'chat-unblocked' => '$1 ने $2 ला {{GENDER:$3|अप्रतिबंधित केले आहे}}',
	'chat-private-message' => '(खाजगी संदेश)',
	'chat-user-is-moderator' => 'हा सदस्य नियामक (मॉडरेटर) {{GENDER:$1|आहे}}',
	'chat-you-are-moderator' => 'आपण नियामक {{GENDER:$1|आहात}}',
	'chat-joined' => '$1 चॅटशी जुळला आहे', # Fuzzy
	'chat-left' => '$1 चॅटच्या {{GENDER:$2|बाहेर गेला आहे}}',
	'chat-topic' => '{{SITENAME}}च्या चॅट वर आपले स्वागत आहे.([[Special:Log/chat|चॅट नोंदी]])',
	'chat-sidebar-online' => 'चॅट करीत असलेले ऑनलाईन सदस्य',
	'chat-sidebar-join' => 'चॅटला जुळा',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => 'चॅटच्या नोंदी',
	'log-description-chat' => 'मिडियाविकी चॅट तसेच सदस्य किक्स द्वारे पाठविण्यात आलेला संदेश',
	'logentry-chat-kick' => '$1 ने $4 ला {{GENDER:$3|लाथाडले}}', # Fuzzy
	'log-show-hide-chat' => '$1 चॅट नोंदी',
	'group-chatmod' => 'चॅट नियामक (मॉडरेटर)',
	'group-chatmod-member' => '{{GENDER:$1|चॅट नियामक}}',
	'grouppage-chatmod' => '{{ns:project}}:चॅट नियामक',
	'group-blockedfromchat' => 'सदस्यास चॅट करण्यास प्रतिबंधित आहे',
	'group-blockedfromchat-member' => '{{GENDER:$1|चॅटपासून प्रतिबंधित}}',
	'grouppage-blockedfromchat' => '{{ns:project}}:सदस्यांना चॅटपासून प्रतिबंधित केले आहे',
	'right-mediawikichat-chat' => 'Special:चॅट चा वापर करा',
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
	'chat-minutes-ago' => '{{PLURAL:$1|1 minuto|$1 minuto}} atrás',
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
	'chat-just-now' => 'только что',
	'chat-a-minute-ago' => 'минуту назад',
	'chat-minutes-ago' => '{{PLURAL:$1|$1 минуту|$1 минут|$1 минуты}} назад',
	'chat-yesterday' => 'вчера',
	'chat-private-message' => '(личное сообщение)',
	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png',
	'log-name-chat' => 'Лог чата',
	'group-chatmod' => 'Модераторы чата',
);

/** Sinhala (සිංහල)
 * @author Thushara
 */
$messages['si'] = array(
	'chat-blocked-from-chat' => 'ඔබ මෙම කතිකාවට සහහාගී වීම වලක්වා ඇත.',
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
 * @author Qiyue2001
 */
$messages['zh-hans'] = array(
	'chat' => '聊天',
	'chat-desc' => '提供一个轻量级的聊天客户端和服务器',
	'chat-type-your-message' => '键入您的消息',
	'chat-type-your-private-message' => '键入您的私人信息',
	'chat-no-other-users' => '没有其他用户在线',
	'chat-blocked-from-chat' => '您已经被阻止聊天。',
	'chat-not-allowed' => '您不允许聊天，请先尝试登录',
	'chat-just-now' => '刚刚',
	'chat-a-minute-ago' => '一分钟前',
	'chat-minutes-ago' => '$1分钟前',
	'chat-yesterday' => '昨天',
	'chat-youve-been-kicked' => '你已经被$1{{GENDER:$2|踢出}}。刷新页面以聊天',
	'chat-you-kicked' => '您{{GENDER:$2|踢了}}$1',
	'chat-kicked' => '$1{{GENDER:$3|踢了}}$2',
	'chat-kick' => '踢',
	'chat-youve-been-blocked' => '你已经被$1{{GENDER:$2|阻止}}聊天。',
	'chat-you-blocked' => '您{{GENDER:$2|阻止了}}$1',
	'chat-blocked' => '$1{{GENDER:$3|阻止了}}$2',
	'chat-block' => '阻止',
	'chat-private-message' => '(私人消息)',
	'chat-sidebar-online' => '聊天中的在线用户：',
	'chat-sidebar-join' => '加入聊天',
	'log-name-chat' => '聊天记录',
	'log-show-hide-chat' => '$1聊天记录',
	'group-blockedfromchat' => '被阻止聊天的用户',
	'group-blockedfromchat-member' => '{{GENDER:$1|阻止聊天}}',
	'grouppage-blockedfromchat' => '{{ns:project}}：阻止聊天的用户', # Fuzzy
);
