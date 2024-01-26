=== Bradmax Player ===
Contributors: bradmax
Tags: video, html5, video streaming, HLS, MPEG-DASH, MS Smooth Streaming, embed video, responsive, subtitles, wpvideo, google analytics, google analytics video
Requires at least: 4.2
Tested up to: 6.3
Stable tag: 1.1.27
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Author URI: https://bradmax.com/site/en/#contact
Author: bradmax
Version: 1.1.27
Donate link: https://www.paypal.com/donate/?hosted_button_id=PJPZBHU2UC52N

Embed video stream easily in WordPress using Bradmax Player. Use responsive HTML5 video player for playing HLS, MPEG-DASH, MS Smooth Streaming streams.

== Description ==

[Bradmax Player](https://bradmax.com/site/en/) is a plugin, which supports video streams playback on desktops and mobile devices. If you have access to video streaming in formats:
- HLS
- MPEG-DASH
- MS Smooth Streaming
or simple mp4, webM, ogg files, then you can watch them on your site with Bradmax Player. It is even supporting HLS or MS Smooth Streaming playback on platforms / devices, which
usually not support them. In such cases video is "transconded" on-fly in your browser during playback.

Player support also:

* poster image - Custom image from video, which is displayed on player before playback.
* subtitles - Embedded in HLS, MPEG-DASH, MS Smooth Streaming video stream or from external files in SRT, VTT file formats.
* basic video statistics for Google Analytics - Just paste your Google Analytics tracker id into player settings for collecting information about video views and watched time.
* video chapters & time markers - Extending progressbar with additional information about video chapters or time markers.

= Requirements =

* A self-hosted website running on WordPress CRM.

= Bradmax Player Features =

* Embed video streams into a post/page or anywhere on your WordPress site (supported streaming formats HLS, MPEG-DASH, MS Smooth Streaming).
* Embed video files (MP4, WebM, Ogg) into your page.
* Embed responsive videos for a better user experience while viewing from a mobile device.
* Embed videos with poster images.
* Automatically play a video when the page is rendered.
* Embed videos uploaded to your WordPress media library using direct links in the shortcode.
* No setup required, simply install and start embedding videos.
* Lightweight and compatible with the latest version of WordPress
* Clean and sleek player with no watermark.
* Player customisation is available (change skin, colors, logo, etc.). It requires only sign-up on https://bradmax.com/site/en/signup . It's free and basic version of player is also free.
* Embed video with subtitles (subtitles loaded from stream or from SRT, VTT files).
* Collect basic statistics about video playback in your Google Analytics account.
* Playback of DRM protected video (only in paid version for custom player downloaded from bradmax.com).

= Bradmax Player Plugin Usage =

In order to embed a video create a new post/page and use the following shortcode:

`[bradmax_video url="https://bradmax.com/static/video/tos/big_buck_bunny.m3u8" duration="596" poster="https://bradmax.com/static/images/startsplash.jpg"]`

* "url" is the location of your streaming. You need to replace the sample URL with the actual URL of the video stream.
* "duration" contain length in seconds of video, so it can be displayed on player before staring playback.
* "poster" is location of poster image, which should be displayed on player. Replace sample URL with link of your image.

= Video playback statistics with Google Analytics =

Player can collect basic statistics for video playback. You just need to copy your "Tracking ID" from Google Analytics page into player settings.

For finding "Tracking ID" please open: [Google Analytics](https://analytics.google.com) > Admin > Tracking Info > Tracking Code .

"Tracker ID" is code having form "UA-XXXXXXXX-X", where X is 0-9 digit and you have to copy it into ga_tracker_id video shortcode option (see section below).

Player collects video playback details as "Events" in your Google Analytics account. There are available in sections:

* Google Analytics panel > Real-Time > Events
* Google Analytics panel > Behaviour > Events

Player is sending events:

* event category: view , event action: started (send on starting video playback)
* event category: player event, event action: playing/paused (send on play/pause video)
* event category: progress seconds, event action: progress seconds (send every 10 sec of playback)

For each media distinction in statistics you have to specify in video shortcode option "media_id". Then each event got additionaly "Event Label" with data provided from media_id parameter. media_id can be any text, which you want to define, but it is recomended to keep it short.

= Video Shortcode Options =

The following options are supported in the shortcode.

**Autoplay**

Causes the video file to automatically play when the page loads.
Note: Currenlty this option is working only on desktop devices with muted sound (see "Mute" shortcode). On mobile devices (phones, tablets, etc.) this option is not working.
It is platform limitation and clicking on video is required for starting playback.

`[bradmax_video url="http://example.com/hls_stream.m3u8" autoplay="true"]`

**Mute**

Causes the video starts with muted sound. This option is usefull for starting video automatically with "autoplay" option.

`[bradmax_video url="http://example.com/hls_stream.m3u8" autoplay="true" mute="true"]`

**Duration**

Defines length of video stream in seconds. Can contain fraction of second. It is required for displaying duration of video before staring playback.

`[bradmax_video url="http://example.com/hls_stream.m3u8" duration="100.1"]`

**Poster**

Defines image to show as placeholder before the video plays.

`[bradmax_video url="http://example.com/hls_stream.m3u8" poster="http://example.com/wp-content/uploads/poster.jpg"]`

**Pip**

Enables additional button in player skin for enabling/disabling Picture-in-Picture mode. When PIP mode is enabled then player is detached from browser and user can watch video over other screens.
Note: This feature requires player at last v2.12.0 version. If you are using older customized player (changed colors, skin, etc.), then you have to generate new one on bradmax.com page.
PIP button appears after starting playback. Before it is blocked by browser.

`[bradmax_video url="http://example.com/hls_stream.m3u8" pip="true"]`

**Chapters & Time markers**

Defines additional info for video chapters. Such information will be presented on progress bar. Format for video chapters is similar to Youtube chapters in description.
Each chapter entry should be in separated line or separated from other entries using semicolon (;). Entry begins with start time of chapter and folowed by label for chapter.
Chapters use "chapters" attribute name and time markers "time_markers".

`[bradmax_video url="https://bradmax.com/static/video/tos/440272.mpd" duration="100.1" chapters="
0:00 - Intro
0:23 - 1. Preparations
3:18 - 2. Simulation
7:18 - 3. Defense
8:48 - 4. Forgiveness
9:38 - Credits
"
time_markers="
 1:40 - Decoy
 3:20 - Simulation start 
 5:30 - They are comming ...
 8:08 - Rope slide
11:14 - Credits rope slide
"]`

Example for semicolons instead of new lines - all in one line. Sometimes wordpress can have problems with multiline in shortcodes. Using semicolons will solve it.

`[bradmax_video url="https://bradmax.com/static/video/tos/440272.mpd" duration="100.1" chapters="0:00 - Intro ; 0:23 - 1. Preparations ; 3:18 - 2. Simulation ;7:18 - 3. Defense ; 8:48 - 4. Forgiveness ; 9:38 - Credits"]`


**Class**

Defines CSS class, which should be added into player box on page (customizing view on WordPress page).

`[bradmax_video url="http://example.com/hls_stream.m3u8" class="my-custom-player-css-class"]`

**Style**

Defines CSS style string, which should be added into player for on page (customizing view on WordPress page).

`[bradmax_video url="http://example.com/hls_stream.m3u8" style="width:400px;height:200px;border:solid 1px gray"]`

**Subtitles**

Defines list of subtitles files (one file per language) for video. Subtitles files has to be in SRT or VTT format (file extension *.srt or *.vtt). Format for subtitles list subtitles="LANG_CODE=FILE_LINK LANG_CODE=FILE_LINK ...", where LANG_CODE is two letter language code (ISO 639-1 standard - https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes) for defining subtitles language. FILE_LINK is link to file stored on some HTTP server, which player will be able to download during playback.

Working example (subtitles in Czech language):

`[bradmax_video url="https://bradmax.com/static/video/tos/tesla/tesla.m3u8" subtitles="cz=https://bradmax.com/static/video/tos/tesla/tesla_cz.srt"]`

Example with multiple languages for video:

`[bradmax_video url="http://example.com/hls_stream.m3u8" subtitles="en=https://example.com/subtitles_en.srt cz=https://example.com/subtitles_cz.srt sk=https://example.com/subtitles_sk.srt"]`

**ga_tracker_id**

Defines Google Analytics tracker id. When defined video playback is tracked in your Google Analytics account in "Events" sections.

"Tracker ID" is code having form "UA-XXXXXXXX-X", where X is 0-9 digit and is located in [Google Analytics](https://analytics.google.com) > Admin > Tracking Info > Tracking Code.

Example:

`[bradmax_video url="http://example.com/hls_stream.m3u8" ga_tracker_id="UA-XXXXXXXX-X" media_id="my example stream"]`

**media_id**

This parameter is used, when Google Analytics plugin is active (see ga_tracker_id video shortcode). It is used for each media distinction, so for each different video diferent value should be provided. It can be any text, but it is recomended to keep it short.

**Alternative stream formats (url_2, url_3, url_4)**

If you got video content (same title/media) in multiple formats eg. MPEG-DASH, HLS, MS Smooth Streaming you can provide them to player using shortcodes url_2, url_3, url_4 . Player automatically choses format with best support for device. You can put links in any order.

Example for video with many formats (MPEG-DASH and HLS):

`[bradmax_video url="https://bradmax.com/static/video/tos/440272.mpd" url_2="https://bradmax.com/static/video/tos/440272.m3u8"]`

**live_**

Player supports playback for live stream transmissions (HLS, MPEG-DASH, MS Smooth Streaming). For such transmissions are additonal parameters, which improve user experience.

* live_end_date: Date time as ISO 8601 string (https://en.wikipedia.org/wiki/ISO_8601). Examples: “2020-06-10T20:00:00Z” (2020-06-10 20:00:00 UTC time zone), “2020-06-12T18:00:00+08:00” (2020-06-12 18:00:00 GMT+8 time zone). If defined, then player is able to detect end of live stream transmission. Without it player will be assume end of transmission and end splash screen will be presented.
* live_thank_you_image_url: Link to custom end splash image, which should be shown after end of live stream transmission. If not defined then default start splash will be shown after end of transmission.
* live_waiting_for_transmission_image_url: Link to custom waiting for transmission image, which should be shown when player is waiting for live stream transmission start. Requires live_end_date parameter for correct work - player is aware if it is before or after transmission.
* live_low_latency_mode: Indicates if lowLatencyMode should be enabled. By default it is disabled. When live_low_latency_mode then latency optimization is used at the expense of quality (stream is in lower quality and can buffer more frequently, but with much lower latency). This mode is working only for HLS and MPEG-DASH streams. example configuration


Examples:

`[bradmax_video url="http://example.com/hls_live_stream.m3u8" live_waiting_for_transmission_image_url="https://bradmax.com/static/images/waiting_for_transmission.jpg" live_thank_you_image_url="https://bradmax.com/static/images/thankyou_endsplash.jpg" live_end_date="2020-06-14T14:00:00+08:00"]`

`[bradmax_video url="http://example.com/hls_live_stream.m3u8" live_low_latency_mode="true"]`

**drm_**

Player supports playback for DRM protected video - paid version of player downloaded from bradmax.com is required. Short codes for DRM configuration:

* drm_prov: Type of provider. Mark "default" for enabling support DRMs for selected content. Available values: ['default', 'ezdrm', 'keyos']
* drm_widevine_url: URL to Widevine DRM license server. It is required for decrypting Widevine protected videos.
* drm_widevine_cust_data: Custom data in base64 encoded format for Widevine.
* drm_playready_url: URL to MS PlayReady DRM license server. It is required for decrypting PlayReady protected videos.
* drm_playready_cust_data: Custom data in base64 encoded format for MS PlayReady.
* drm_fairplay_url: URL to FairPlay DRM license server. It is required for decrypting FairPlay protected videos.
* drm_fairplay_cust_data: Custom data in base64 encoded format for FairPlay.
* drm_fairplay_cert_url: URL to FairPlay certificate server.

Example:

`[bradmax_video url="https://example.com/drm_protected_stream.mpd" drm_prov="default" drm_widevine_url="https://drm.example.com/license"]`

== Installation ==

1. Go to the Add New plugins screen in your WordPress Dashboard
2. Click the upload tab
3. Browse for the plugin file (bradmax-player.zip) on your computer.
4. Click "Install Now" and then hit the activate button.

Or install directly from WordPress Plugin Directory.

== Frequently Asked Questions ==

= Can this plugin be used to embed video streams in WordPress? =

Yes.

= Is HLS supported by this player =

Yes, and what more HLS streams can be displayed even on desktop devices, which natively not support HLS streams.

= Are the videos embedded by this plugin playable on iOS devices? =

Yes.

= Can I embed responsive videos using this plugin? =

Yes.


== Upgrade Notice ==
none

== Changelog ==

= 1.1.27 =

* Upgrading default player to v2.14.287 (general bug fixes and improvements)
** Improvement: Smaller size of player file.
** Improvement: Better sking behaviour on various devices.
** Bug fix: Solving UI problems for mobile devices.
** Bug fix: Playback stability improvement.

= 1.1.26 =

* Correcting Bradmax player JS detection for newest builds from https://bradmax.com/client/panel/ .

= 1.1.25 =

* Upgrading default player to v2.14.249 (general bug fixes)
** Bug fix: Solving problems with fullscreen on iPhone.
** Bug fix: Solving problems ads playback on iOS.
** Bug fix: Solving problem with glitches on video on some Android devices.
* Adding possibility to disable Bradmax Analytics via wordpress short code parameter.

= 1.1.24 =

* Upgrading default player to v2.14.246 (general bug fixes)
** Bug fix: Solving problems with missing buffering event for DASH when seeking live stream & DVR on pause.
** Bug fix: Solving problems with new format of video google ads url (lack of format extension) - incorrectly detected as not supported.
** Bug fix: Solving problems with displaying subtitles on iOS/MacOS&Safari with horisontal video on vertical screen.
** Bug fix: Returning seekable time in DVR info for MPEG-DASH instead of theoretical available time for seeking.
** Bug fix: Solving problem with pre-playback seeking for html5 and simple video files.
** Bug fix: Solving problem with incorrectly triggering error on DRM issue.
** Bug fix: Solving problems with incorrect error on network connection, when there should be load error.
** Bug fix: Solving problem with autoplaying next media, when playback allready successfully started on iOS devices.
** Bug fix: Solving problems with Safari and very short DVR window for HLS.
** Bug fix: Solving problem with sometimes playback freezing on video discontinuity on DASH on LG SmartTVs.
** Bug fix: Solving problems with Safari on iOS and resuming player after device sleep.
** Bug fix: Solving problems with running video in fullscreen on latest Safari for iOS 16.5.
** Bug fix: Solving problems with pre-playback-seeking for HLS on Chrome/FF browsers.
** Bug fix: Solving problems with flickering parts of video on some Android devices after seeking.

= 1.1.23 =

* Upgrading default player to v2.14.160 (general bug fixes)
** Bug fix: Solving problem with HLS playback on Android devices after Chrome browser changes.
** Bug fix: Solving problem with progress bar near end of video on HLS for short VOD.

= 1.1.22 =

* Upgrading default player to v2.14.133 (general bug fixes)
** Bug fix: Solving problem with MPEG-DASH initialization.
** Bug fix: Solving problem with unmuting video before staring playback.
** Bug fix: Solving problem with displaing long audio and subtitles list on small screens.
** Bug fix: Solving problem with missing loader during buffering MPEG-DASH on some devices.

= 1.1.21 =

* Upgrading default player to v2.14.95 (general bug fixes)
** Bug fix: Solving playback problems on iOS/iPad devices.
** Bug fix: Solving problem with playback on Safari browser after recent browser update.
** Bug fix: Improving MPEG-DASH playback stability for live streams (problem on some older devices).
** Bug fix: Solving problem with seeking to live edge on live streaming HLS.

= 1.1.20 =

* Upgrading default player to v2.14.40 (general bug fixes) and time markers / chapters support.

= 1.1.19 =

* Upgrading default player to v2.14.14 (general bug fixes).

= 1.1.18 =

* Solving problems with tracking media_id parameter.

= 1.1.17 =

* Upgrading default player to v2.12.3 (general fixes, live streams support improvements, Picture-In-Picture/PIP support).
* Picture-In-Picture support for short codes (requires at last player v2.12.0) with pip="true".
* Adding support for live stream short codes: live_low_latency_mode, live_end_date, live_thank_you_image_url, live_waiting_for_transmission_image_url. 
* Adding support for midroll_X_vast_url, midroll_X_vast_time_offset, vast short codes.

= 1.1.16 =

* Adding support for postroll_vast_url short code.
* Minor corrections for setting DRM params with short codes.

= 1.1.15 =

* Supporting additional video source URLs in short codes (url_2, url_3, url_4) - for providing alternative stream formats for same video material. Player will choose automatically best format for device.

= 1.1.14 =

* Adding DRM params support for player with enabled DRM support.

= 1.1.13 =

* Solving problems with escaped query params for URLs used in short code.

= 1.1.12 =

* Upgrading default player to v2.10.6 (General player fixes and stability improvements.).

= 1.1.11 =

* Upgrading default player to v2.10.0 (Update for better support MPEG-DASH an MS Smooth Streaming. General player fixes and stability improvements.).

= 1.1.10 =

* Solving problems with player load, when some JS loader library is used on page.

= 1.1.9 =

* Upgrading default player to v2.8.12 (General player fixes and stability improvements. Improved handling for network issues.).

= 1.1.8 =

* Upgrading default player to v2.7.14 (HLS decoder update, UI fixes for MS Edge, MS Explorer browsers and for small screens on mobile devices).

= 1.1.7 =

* Upgrading default player to v2.7.0 (HLS decoder update, better autoplay behaviour, when autoplay blocked by browser).

= 1.1.6 =

* Support to "mute" video with video short code. 
* Upgrading default player to v2.6.1 (new better HLS decoder; possibility to "mute" video by configuration).

= 1.1.5 =

* Solving conflict between default bradmax player and Youtube embed script (upgrading player to v2.5.12).

= 1.1.4 =

* Plugin readme.txt formatting correction.

= 1.1.3 =

* Upgrading default JavaScript player file from version 2.5.5 to 2.5.9 (bugfixes and better HLS and MPEG-DASH live streaming support).
* Implementing Google Analytics video statistics by video shortcode option.

= 1.1.2 =

* Correcting subtitles documentation in readme.txt file.

= 1.1.1 =

* Upgrading default JavaScript player file from version 2.4.11 to 2.5.5 (bugfixes and new features).
** SRT VTT subtitles files support for player.
** Better HLS live stream support.
** Solving problems with HLS playback on Android 6.0.

= 1.0.2 =

* Upgrading default JavaScript player file from version 2.4.2 to 2.4.11 (bugfixes and better support for HLS).

= 1.0.1 =

* First version of WordPress plugin.
