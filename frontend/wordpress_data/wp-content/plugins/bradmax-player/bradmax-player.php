<?php
/*
 Plugin Name: Bradmax Player
 Version: 1.1.27
 Plugin URI: https://bradmax.com/site/en/#contact
 Author: bradmax
 Author URI: https://bradmax.com/site/en/#contact
 Description: Easily embed streaming video using Bradmax player
 Text Domain: bradmax-player
 */

if (!defined('ABSPATH')) {
	exit;
}
if (!class_exists('Bradmax_Player_Plugin')) {

	class Bradmax_Player_Plugin {

		const PLUGIN_VERSION = '1.1.27';
		const BRADMAX_PLAYER_VERSION = '2.14.287';

		const CUSTOMIZED_PLAYER_FILE_PATH = '/assets/js/bradmax_player.js';
		const CUSTOMIZED_PLAYER_MAX_FILE_SIZE = 4000000; // 4MB

		const DEFAULT_CSS_STYLES_FILE_PATH = '/assets/css/style.css';
		const DEFAULT_PLAYER_FILE_PATH = '/assets/js/default_player.js';

		const CUSTOM_PLAYER_TIP_SCREEN_01 = '/assets/img/screen_01_signup.jpg';
		const CUSTOM_PLAYER_TIP_SCREEN_02 = '/assets/img/screen_02_signin.jpg';
		const CUSTOM_PLAYER_TIP_SCREEN_03 = '/assets/img/screen_03_add_player.jpg';
		const CUSTOM_PLAYER_TIP_SCREEN_04 = '/assets/img/screen_04_configure_player.jpg';
		const CUSTOM_PLAYER_TIP_SCREEN_05 = '/assets/img/screen_05_generate_player.jpg';
		const CUSTOM_PLAYER_TIP_SCREEN_06 = '/assets/img/screen_06_download_zip.jpg';
		const CUSTOM_PLAYER_TIP_SCREEN_07 = '/assets/img/screen_07_uncomress_player_for_upload.jpg';

		static $customized_player_file_path_cache;

		static public function init() {
			if (is_admin()) {
				add_filter('plugin_action_links', array('Bradmax_Player_Plugin', 'plugin_action_links'), 10, 2);
			}
			add_action('wp_enqueue_scripts', array('Bradmax_Player_Plugin', 'bradmax_player_enqueue_scripts'));
			add_action('admin_menu', array('Bradmax_Player_Plugin', 'add_options_menu'));

			add_shortcode('bradmax_video', array('Bradmax_Player_Plugin', 'bradmax_video_embed_handler'));
			// Allows shortcode execution in the widget, excerpt and content.
			add_filter('widget_text', 'do_shortcode');
			add_filter('the_excerpt', 'do_shortcode', 12);
			add_filter('the_content', 'do_shortcode', 12);
		}

		static public function plugin_action_links($links, $file) {
			if ($file == plugin_basename(dirname(__FILE__) . '/bradmax-player.php')) {
				$links[] = '<a href="options-general.php?page=bradmax-player-settings">'.__('Settings', 'bradmax-player').'</a>';
			}
			return $links;
		}

		static public function bradmax_player_enqueue_scripts() {
			if (!is_admin()) {
				$plugin_url = plugins_url('', __FILE__);
				wp_register_style('bradmax-player', $plugin_url . self::DEFAULT_CSS_STYLES_FILE_PATH);
				wp_enqueue_style('bradmax-player');

				if(file_exists(self::get_customized_player_file_path())) {
					// Add custom player.
					$file_stat = stat(self::get_customized_player_file_path());
					wp_register_script('bradmax-player', $plugin_url . self::CUSTOMIZED_PLAYER_FILE_PATH, array(), $file_stat['mtime'], false);
					wp_enqueue_script('bradmax-player');
				} else {
					// Use default player - already embeded in wordpress plugin.
					wp_register_script('bradmax-player', $plugin_url . self::DEFAULT_PLAYER_FILE_PATH, array(), self::BRADMAX_PLAYER_VERSION, false);
					wp_enqueue_script('bradmax-player');
				}
			}
		}

		static public function add_options_menu() {
			if (is_admin()) {
				add_options_page(
					__('Bradmax Player Settings', 'bradmax-player'),
					__('Bradmax Player', 'bradmax-player'),
					'manage_options',
					'bradmax-player-settings',
					array('Bradmax_Player_Plugin', 'options_page')
				);
			}
		}

		static private function upload_customized_player_js($file_rec) {
			// Check upload status / error.
			if(!isset($file_rec['error']) || ($file_rec['error'] != UPLOAD_ERR_OK)) {
				if($file_rec['error'] == UPLOAD_ERR_NO_FILE) {
					return array('error' => 'No file was sent. Please select file to upload and click button "Upload"');
				}
				if(($file_rec['error'] == UPLOAD_ERR_INI_SIZE) || ($file_rec['error'] == UPLOAD_ERR_FORM_SIZE)) {
					return array('error' => 'Server file size limit reached.');
				}
				return array('error' => 'File cannot be uploaded into server.');
			}

			// Check file extension.
			if(!isset($file_rec['name']) || !preg_match('/\.js$/i', $file_rec['name'])) {
				return array('error' => 'Invalid file name. It should be JavaScript file. Search for bradmax_player.js file.');
			}

			// Check file size.
			if($file_rec['size'] > self::CUSTOMIZED_PLAYER_MAX_FILE_SIZE) {
				return array('error' => 'It is not player file. File is too big.');
			}

			// Check if file contain test pattern.
			$content = file_get_contents($file_rec['tmp_name']);
			if(strpos($content, 'bradmax_player_v') === false && strpos($content, 'bradmax.player.') === false) {
				return array('error' => 'File does not contain Bradmax player.');
			}
			unset($content);

			// Move file into prepared place.
			if(!move_uploaded_file($file_rec['tmp_name'], self::get_customized_player_file_path())) {
				return array('error' => 'Unexpected error occured after file upload. File cannot be copied into plugin directory.');
			}

			return array('success' => true);
		}

		static private function get_customized_player_info() {
			$result = array();

			// Get date of upload.
			$finfo = stat(self::get_customized_player_file_path());
			$result['modification_ts'] = $finfo['mtime'];

			// Read player file.
			$content = file_get_contents(self::get_customized_player_file_path());

			// Get player version.
			$result['version'] = 'Unknown';
			if(preg_match('/bradmax_player_v([0-9\.]+)/', $content, $m)) {
				$result['version'] = $m[1];
			}
			if(preg_match('/getPluginVersion\(\){return "v([0-9\.]+)"}/', $content, $m)) {
				$result['version'] = $m[1];
			}

			// Get player skin
			$result['skin'] = 'Unknown';
			if(preg_match('/"skin":"([^"]+)"/', $content, $m)) {
				$result['skin'] = $m[1];
			}
			if(preg_match('/theme\/([^\/]+)\/layout.html"/', $content, $m)) {
				$result['skin'] = $m[1];
			}

			return $result;
		}

		static public function get_customized_player_file_path() {
			if(self::$customized_player_file_path_cache == null) {
				self::$customized_player_file_path_cache = dirname(__FILE__).self::CUSTOMIZED_PLAYER_FILE_PATH;
			}
			return self::$customized_player_file_path_cache;
		}

		static private function show_help_tip() {
			$plugin_url = plugins_url('', __FILE__);
			include dirname(__FILE__).'/views/show-help-tip.php';
		}

		static public function options_page() {
			$upload_info = array();
			if(isset($_FILES['bradmax_player_file'])) {
				// Upload provided customized player.
				$upload_info = self::upload_customized_player_js($_FILES['bradmax_player_file']);
			}

			if(isset($_REQUEST['remove_player'])) {
				// Remove customized player.
				unlink(self::get_customized_player_file_path());
			}

			$custom_player_exists = is_file(self::get_customized_player_file_path());
			include dirname(__FILE__).'/views/options-page.php';
		}

		static private function bradmax_video_build_player_wrapper($player_wrapper_id, $params) {
			// Use double wrappers for having auto-scalling container to full width with aspect ratio 16:9.
			$player_wrapper_class_str = isset($params['class']) ? ('class="'.$params['class'].'" ') : '';
			$player_wrapper_str = <<<EOT
				<div style="width: 100%;padding-bottom: 56.25%;position: relative;" $player_wrapper_class_str>
					<div id="$player_wrapper_id" style="position: absolute;top: 0; bottom: 0; left: 0; right: 0;"></div>
				</div>
EOT;

			// If style is defined use just simple single wrapper for player and inline styles.
			if(!empty($params['style'])) {
				$player_wrapper_style_str = $params['style'];
				$player_wrapper_str = <<<EOT
					<div id="$player_wrapper_id" style="$player_wrapper_style_str" $player_wrapper_class_str></div>
EOT;
			}

			return $player_wrapper_str;
		}

		static private function bradmax_build_drm_config($params) {
			$drm_config = array(
				'provider' => $params['drm_prov'],
			);
			if(!empty($params['drm_widevine_url'])) {
				if(!isset($drm_config['widevine'])) {
					$drm_config['widevine'] = array();
				}
				$drm_config['widevine']['laUrl'] = html_entity_decode($params['drm_widevine_url']);
			}
			if(!empty($params['drm_widevine_cust_data'])) {
				if(!isset($drm_config['widevine'])) {
					$drm_config['widevine'] = array();
				}
				$drm_config['widevine']['customData'] = $params['drm_widevine_cust_data'];
			}
			if(!empty($params['drm_playready_url'])) {
				if(!isset($drm_config['widevine'])) {
					$drm_config['playready'] = array();
				}
				$drm_config['playready']['laUrl'] = html_entity_decode($params['drm_playready_url']);
			}
			if(!empty($params['drm_playready_cust_data'])) {
				if(!isset($drm_config['playready'])) {
					$drm_config['playready'] = array();
				}
				$drm_config['playready']['customData'] = $params['drm_playready_cust_data'];
			}
			if(!empty($params['drm_fairplay_url'])) {
				if(!isset($drm_config['fairplay'])) {
					$drm_config['fairplay'] = array();
				}
				$drm_config['fairplay']['laUrl'] = html_entity_decode($params['drm_fairplay_url']);
			}
			if(!empty($params['drm_fairplay_cust_data'])) {
				if(!isset($drm_config['fairplay'])) {
					$drm_config['fairplay'] = array();
				}
				$drm_config['fairplay']['customData'] = $params['drm_fairplay_cust_data'];
			}
			if(!empty($params['drm_fairplay_cert_url'])) {
				if(!isset($drm_config['fairplay'])) {
					$drm_config['fairplay'] = array();
				}
				$drm_config['fairplay']['certUrl'] = html_entity_decode($params['drm_fairplay_cert_url']);
			}
			return $drm_config;
		}

		static private function bradmax_build_time_markers_and_chapters_config($params) {
			$result = array();
			$duration = 10000;
			if(!empty($params['duration'])) {
				$duration = floatval($params['duration']);
			}
			if(!empty($params['chapters'])) {
				$segments = array();
				$parts = preg_split('/[\n\r;]+/', strip_tags($params['chapters']));
				foreach($parts as $line) {
					$m = null;
					if(!preg_match('/(([0-9]+:)?([0-9]+:)?[0-9]+) *-? *(.*)/', $line, $m)) {
						continue;
					}
					$segments[] = array(
						't' => self::parse_time($m[1]),
						'label' => trim($m[4])
					);
				}
				if(!empty($segments)) {
					for($pos = 0; $pos < count($segments); $pos++) {
						$nextTime = isset($segments[$pos + 1]) ? $segments[$pos + 1]['t'] : $duration;
						$segments[$pos]['duration'] = $nextTime - $segments[$pos]['t'];
						unset($segments[$pos]['t']);
					}
					$result['segments'] = $segments;
				}
			}
			if(!empty($params['time_markers'])) {
				$markers = array();
				$parts = preg_split('/[\n\r;]+/', strip_tags($params['time_markers']));
				foreach($parts as $line) {
					$m = null;
					if(!preg_match('/(([0-9]+:)?([0-9]+:)?[0-9]+) *-? *(.*)/', $line, $m)) {
						continue;
					}
					$markers[] = array(
						'time' => self::parse_time($m[1]),
						'label' => trim($m[4])
					);
				}
				if(!empty($markers)) {
					$result['markers'] = $markers;
				}
			}
			return $result;
		}

		static private function parse_time($time_str) {
			$result = 0;
			$parts = explode(':', $time_str);
			foreach($parts as $p) {
				$result *= 60;
				$result += floatval($p);
			}
			return $result;
		}

		static public function bradmax_video_embed_handler($atts) {
			$params = shortcode_atts(array(
				'url' => '',
				'url_2' => '',
				'url_3' => '',
				'url_4' => '',
				'duration' => '',
				'autoplay' => 'false',
				'mute' => 'false',
				'poster' => '',
				'pip' => 'false',
				'live_low_latency_mode' => 'false',
				'live_end_date' => '',
				'live_thank_you_image_url' => '',
				'live_waiting_for_transmission_image_url' => '',
				'class' => '',
				'style' => '',
				'subtitles' => '',
				'ga_tracker_id' => '',
				'preroll_vast_url' => '',
				'postroll_vast_url' => '',
				'vmap' => '',
				'midroll_1_vast_url' => '',
				'midroll_1_vast_time_offset' => '',
				'midroll_2_vast_url' => '',
				'midroll_2_vast_time_offset' => '',
				'midroll_3_vast_url' => '',
				'midroll_3_vast_time_offset' => '',
				'media_id' => '',
				'drm_prov' => '',
				'drm_widevine_url' => '',
				'drm_widevine_cust_data' => '',
				'drm_playready_url' => '',
				'drm_playready_cust_data' => '',
				'drm_fairplay_url' => '',
				'drm_fairplay_cust_data' => '',
				'drm_fairplay_cert_url' => '',
				'chapters' => '',
				'time_markers' => '',
				'ba_off' => '',
				'ba_gdpr_req' => '',
			), $atts);

			// Player config.
			if(empty($params['url'])){
				return 'Error: You need to specify the src of the video file';
			}
			$player_config = array(
				'dataProvider' => array(
					'source' => array(
						array('url' => html_entity_decode($params['url']))
					)
				)
			);
			// Optional parameters - additonal sources for the same video material.
			foreach(['url_2', 'url_3', 'url_3'] as $url_param) {
				if(!empty($params[$url_param])) {
					$player_config['dataProvider']['source'][]
						= array('url' => html_entity_decode($params[$url_param]));
				}
			}
			if(!empty($params['duration'])) {
				$player_config['dataProvider']['duration'] = floatval($params['duration']);
			}
			if(!empty($params['autoplay']) && (strtolower($params['autoplay']) == 'true')) {
				$player_config['autoplay'] = true;
			}
			if(!empty($params['mute']) && (strtolower($params['mute']) == 'true')) {
				$player_config['mute'] = true;
			}
			if(!empty($params['poster'])) {
				$player_config['dataProvider']['splashImages'] = array(
					array('url' => html_entity_decode($params['poster']))
				);
			}
			if(!empty($params['pip']) && (strtolower($params['pip']) == 'true')) {
				$player_config['pictureInPictureButtonVisible'] = true;
			}
			if(!empty($params['live_end_date'])) {
				if(!isset($player_config['dataProvider']['liveStream'])) {
					$player_config['dataProvider']['liveStream'] = [];
				}
				$player_config['dataProvider']['liveStream']['endDate'] = $params['live_end_date'];
			}
			if(!empty($params['live_low_latency_mode']) && (strtolower($params['live_low_latency_mode']) == 'true')) {
				if(!isset($player_config['dataProvider']['liveStream'])) {
					$player_config['dataProvider']['liveStream'] = [];
				}
				$player_config['dataProvider']['liveStream']['lowLatencyMode'] = true;
			}
			if(!empty($params['live_thank_you_image_url'])) {
				if(!isset($player_config['dataProvider']['liveStream'])) {
					$player_config['dataProvider']['liveStream'] = [];
				}
				$player_config['dataProvider']['liveStream']['thankYouImageUrl'] = html_entity_decode($params['live_thank_you_image_url']);
			}
			if(!empty($params['live_waiting_for_transmission_image_url'])) {
				if(!isset($player_config['dataProvider']['liveStream'])) {
					$player_config['dataProvider']['liveStream'] = [];
				}
				$player_config['dataProvider']['liveStream']['waitingForTransmissionImageUrl'] = html_entity_decode($params['live_waiting_for_transmission_image_url']);
			}
			if(!empty($params['subtitles'])) {
				$player_config['dataProvider']['subtitlesSets'] = array();
				$entires = preg_split('/[ \t\n\r]+/', $params['subtitles']) ;
				foreach($entires as $entry) {
					$parts = explode("=", $entry, 2);
					if(count($parts) != 2) {
						continue;
					}
					$lang_code = trim($parts[0]);
					$lang_file_url = trim($parts[1]);
					$player_config['dataProvider']['subtitlesSets'][] = array(
						'languageCode' => $lang_code,
						'url' => html_entity_decode($lang_file_url)
					);
				}

				// Choose first language from list as default one.
				if(count($player_config['dataProvider']['subtitlesSets']) > 0) {
					$player_config['subtitles'] = $player_config['dataProvider']['subtitlesSets'][0]['languageCode'];
				}
			}
			if(!empty($params['media_id'])) {
				$player_config['dataProvider']['id'] = $params['media_id'];
			}
			if(!empty($params['ga_tracker_id'])) {
				$player_config['googleAnalytics'] = array();
				$player_config['googleAnalytics']['trackerId'] = $params['ga_tracker_id'];
			}
			if(!empty($params['ba_gdpr_req'])) {
				$player_config['bradmaxAnalytics'] = array();
				$player_config['bradmaxAnalytics']['gdprAgreeRequired'] = $params['ba_gdpr_req'];
			}
			if(!empty($params['ba_off'])) {
				$player_config['bradmaxAnalytics'] = array();
				$player_config['bradmaxAnalytics']['clientToken'] = null;
			}
			if(!empty($params['preroll_vast_url'])) {
				$player_config['advertisement'] = array(
					'breaks' => array(
						array('timeOffset' => 'start', 'vast' => html_entity_decode($params['preroll_vast_url']))
					)
				);
			}
			if(!empty($params['postroll_vast_url'])) {
				if(!isset($player_config['advertisement']['breaks'])) {
					$player_config['advertisement'] = array(
						'breaks' => array()
					);
				}
				$player_config['advertisement']['breaks'][]
					= array('timeOffset' => 'end', 'vast' => html_entity_decode($params['postroll_vast_url']));
			}
			if(!empty($params['vmap'])) {
				if(!isset($player_config['advertisement'])) {
					$player_config['advertisement'] = [];
				}
				$player_config['advertisement']['vmap'] = html_entity_decode($params['vmap']);
			}
			$midrols_idsx = [1,2,3];
			foreach($midrols_idsx as $midrol_idx) {
				if(!empty($params['midroll_'.$midrol_idx.'_vast_url']) && !empty($params['midroll_'.$midrol_idx.'_vast_time_offset'])) {
					if(!isset($player_config['advertisement'])) {
						$player_config['advertisement'] = [];
					}
					if(!isset($player_config['advertisement']['breaks'])) {
						$player_config['advertisement']['breaks'] = [];
					}
					$player_config['advertisement']['breaks'][] =
						array('vast' => html_entity_decode($params['midroll_'.$midrol_idx.'_vast_url']), 'timeOffset' => $params['midroll_'.$midrol_idx.'_vast_time_offset']);
				}
			}
			if(!empty($params['drm_prov'])) {
				foreach(array_keys($player_config['dataProvider']['source']) as $source_key) {
					$player_config['dataProvider']['source'][$source_key]['drm'] = self::bradmax_build_drm_config($params);
				}
			}
			if(!empty($params['chapters']) || !empty($params['time_markers'])) {
				$player_config['dataProvider']['progress'] = self::bradmax_build_time_markers_and_chapters_config($params);
			}
			$player_uniqid = uniqid();
			$player_callback_name = 'bradmaxPlayerInit_'. $player_uniqid;
			$player_config_str = json_encode($player_config);
			$player_config_var_name = 'bradmaxPlayerConfig_'. $player_uniqid;

			// Player wrapper.
			$player_wrapper_id = "bradmax-player-" . $player_uniqid;
			$player_wrapper_str = self::bradmax_video_build_player_wrapper($player_wrapper_id, $params);

			$output = <<<EOT
	$player_wrapper_str
	<script type="text/javascript">
		function $player_callback_name() {
			var $player_config_var_name = $player_config_str;
			var element = document.getElementById("$player_wrapper_id");
			var player = window.bradmax.player.create(element, $player_config_var_name);
			// Back compability.
			if(!window.player) {
				window.player = player;
			}
		}
		if(window.bradmax && window.bradmax.player) {
			$player_callback_name();
		} else {
			window.addEventListener('load', $player_callback_name);
		}
	</script>
EOT;
			return $output;
		}
	}

	Bradmax_Player_Plugin::init();
}

