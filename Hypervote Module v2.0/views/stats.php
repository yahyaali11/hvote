<?php if ( ! defined( 'APP_VERSION' ) ) {
	die( "Yo, what's up?" );} ?>
<!DOCTYPE html>
<html lang="<?php echo ACTIVE_LANG; ?>">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
		<meta name="theme-color" content="#fff">

		<meta name="description" content="<?php echo site_settings( 'site_description' ); ?>">
		<meta name="keywords" content="<?php echo site_settings( 'site_keywords' ); ?>">

		<link rel="icon" href="<?php echo site_settings( 'logomark' ) ? site_settings( 'logomark' ) : APPURL . '/assets/img/logomark.png'; ?>" type="image/x-icon">
		<link rel="shortcut icon" href="<?php echo site_settings( 'logomark' ) ? site_settings( 'logomark' ) : APPURL . '/assets/img/logomark.png'; ?>" type="image/x-icon">

		<link rel="stylesheet" type="text/css" href="<?php echo APPURL . '/assets/css/plugins.css?v=' . VERSION; ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo APPURL . '/assets/css/core.css?v=' . VERSION; ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo PLUGINS_URL . '/' . $idname . '/assets/css/core.css?v=41' . VERSION; ?>">

		<title><?php echo __( 'Stats' ); ?></title>
	</head>

	<body class="<?php echo $AuthUser->get( 'preferences.dark_mode_status' ) ? $AuthUser->get( 'preferences.dark_mode_status' ) == '1' ? 'darkside' : '' : ''; ?>">
		<?php
			$Nav             = new stdClass();
			$Nav->activeMenu = $idname;
			require_once APPPATH . '/views/fragments/navigation.fragment.php';
		?>

		<?php
			$TopBar        = new stdClass();
			$TopBar->title = __( 'Stats' );
			$TopBar->btn   = false;
			require_once APPPATH . '/views/fragments/topbar.fragment.php';
		?>

		<?php require_once __DIR__ . '/fragments/stats.fragment.php'; ?>

		<?php
		function views( $days ) {
			$views = 0;
			foreach ( $days as $d ) {
				$views = $views + $d->get( 'view_count' );
			}
			return $views;
		}

		function slides( $days ) {
			$slides = 0;
			foreach ( $days as $d ) {
				$slides = $slides + $d->get( 'sliders_count' );
			}
			return $slides;
		}

		function slides_today( $Today ) {
			$slides_today = 0;
			foreach ( $Today as $td ) {
				$slides_today = $slides_today + $td->get( 'sliders_count' );
			}
			return $slides_today;
		}


		function polls( $days ) {
			$polls = 0;
			foreach ( $days as $d ) {
				$polls = $polls + $d->get( 'voted_poll_count' );
			}
			return $polls;
		}

		function polls_today( $Today ) {
			$poll_today = 0;
			foreach ( $Today as $td ) {
				$poll_today = $poll_today + (int) $td->get( 'voted_poll_count' );
			}
			return $poll_today;
		}

		function ques( $days ) {
			$ques = 0;
			foreach ( $days as $d ) {
				$ques = $ques + $d->get( 'question_answers_count' );
			}
			return $ques;
		}

		function ques_today( $Today ) {
			$ques_today = 0;
			foreach ( $Today as $td ) {
				$ques_today = $ques_today + $td->get( 'question_answers_count' );
			}
			return $ques_today;
		}

		function quiz( $days ) {
			$quiz = 0;
			foreach ( $days as $d ) {
				$quiz = $quiz + $d->get( 'quiz_answers_count' );
			}
			return $quiz;
		}

		function quiz_today( $Today ) {
			$quiz_today = 0;
			foreach ( $Today as $td ) {
				$quiz_today = $quiz_today + $td->get( 'quiz_answers_count' );
			}
			return $quiz_today;
		}

		function massvi( $days ) {
			$massvi = 0;
			foreach ( $days as $d ) {
				$massvi = $massvi + $d->get( 'mass_story_view_count' );
			}
			return $massvi;
		}
		function massvi_today( $Today ) {
			$massvi_today = 0;
			foreach ( $Today as $td ) {
				$massvi_today = $massvi_today + $td->get( 'mass_story_view_count' );
			}
			return $massvi_today;
		}


		function day_name( $days ) {
			$day  = '';
			$data = $days[0]->get( 'date' );
			if ( ! empty( $data ) ) {
				$day = __( date( 'l', strtotime( $data ) ) );
			}
			return $day;
		}

		function countday( $strdate ) {
			$day = '';
			if ( ! empty( $strdate ) ) {
				$day = __( date( 'l', strtotime( '-' . $strdate . ' days' ) ) );
			}
			return $day;
		}

			( $day6stats->getTotalCount() > 0) ? $d6      = views( $day6 ) : $d6 = 0;
			( $day5stats->getTotalCount() > 0 ) ? $d5     = views( $day5 ) : $d5 = 0;
			( $day4stats->getTotalCount() > 0 ) ? $d4     = views( $day4 ) : $d4 = 0;
			( $day3stats->getTotalCount() > 0 ) ? $d3     = views( $day3 ) : $d3 = 0;
			( $day2stats->getTotalCount() > 0 ) ? $d2     = views( $day2 ) : $d2 = 0;
			( $day1stats->getTotalCount() > 0 ) ? $d1     = views( $day1 ) : $d1 = 0;
			( $TodayStats->getTotalCount() > 0 ) ? $today = $today_views : $today = 0;

			( $day6stats->getTotalCount() > 0 ) ? $s6     = slides( $day6 ) : $s6 = 0;
			( $day5stats->getTotalCount() > 0 ) ? $s5     = slides( $day5 ) : $s5 = 0;
			( $day4stats->getTotalCount() > 0 ) ? $s4     = slides( $day4 ) : $s4 = 0;
			( $day3stats->getTotalCount() > 0 ) ? $s3     = slides( $day3 ) : $s3 = 0;
			( $day2stats->getTotalCount() > 0 ) ? $s2     = slides( $day2 ) : $s2 = 0;
			( $day1stats->getTotalCount() > 0 ) ? $s1     = slides( $day1 ) : $s1 = 0;
			( $TodayStats->getTotalCount() > 0 ) ? $stoday = slides_today( $Today ) : $stoday = 0;

			( $day6stats->getTotalCount() > 0 ) ? $p6      = polls( $day6 ) : $p6 = 0;
			( $day5stats->getTotalCount() > 0 ) ? $p5      = polls( $day5 ) : $p5 = 0;
			( $day4stats->getTotalCount() > 0 ) ? $p4      = polls( $day4 ) : $p4 = 0;
			( $day3stats->getTotalCount() > 0 ) ? $p3      = polls( $day3 ) : $p3 = 0;
			( $day2stats->getTotalCount() > 0 ) ? $p2      = polls( $day2 ) : $p2 = 0;
			( $day1stats->getTotalCount() > 0 ) ? $p1      = polls( $day1 ) : $p1 = 0;
			( $TodayStats->getTotalCount() > 0 ) ? $ptoday = polls_today( $Today ) : $ptoday = 0;

			( $day6stats->getTotalCount() > 0 ) ? $ques6      = ques( $day6 ) : $ques6 = 0;
			( $day5stats->getTotalCount() > 0 ) ? $ques5      = ques( $day5 ) : $ques5 = 0;
			( $day4stats->getTotalCount() > 0 ) ? $ques4      = ques( $day4 ) : $ques4 = 0;
			( $day3stats->getTotalCount() > 0 ) ? $ques3      = ques( $day3 ) : $ques3 = 0;
			( $day2stats->getTotalCount() > 0 ) ? $ques2      = ques( $day2 ) : $ques2 = 0;
			( $day1stats->getTotalCount() > 0 ) ? $ques1      = ques( $day1 ) : $ques1 = 0;
			( $TodayStats->getTotalCount() > 0 ) ? $questoday = ques_today( $Today ) : $questoday = 0;

			( $day6stats->getTotalCount() > 0 ) ? $quiz6      = quiz( $day6 ) : $quiz6 = 0;
			( $day5stats->getTotalCount() > 0 ) ? $quiz5      = quiz( $day5 ) : $quiz5 = 0;
			( $day4stats->getTotalCount() > 0 ) ? $quiz4      = quiz( $day4 ) : $quiz4 = 0;
			( $day3stats->getTotalCount() > 0 ) ? $quiz3      = quiz( $day3 ) : $quiz3 = 0;
			( $day2stats->getTotalCount() > 0 ) ? $quiz2      = quiz( $day2 ) : $quiz2 = 0;
			( $day1stats->getTotalCount() > 0 ) ? $quiz1      = quiz( $day1 ) : $quiz1 = 0;
			( $TodayStats->getTotalCount() > 0 ) ? $quiztoday = quiz_today( $Today ) : $quiztoday = 0;

			( $day6stats->getTotalCount() > 0 ) ? $massvi6      = massvi( $day6 ) : $massvi6 = 0;
			( $day5stats->getTotalCount() > 0 ) ? $massvi5      = massvi( $day5 ) : $massvi5 = 0;
			( $day4stats->getTotalCount() > 0 ) ? $massvi4      = massvi( $day4 ) : $massvi4 = 0;
			( $day3stats->getTotalCount() > 0 ) ? $massvi3      = massvi( $day3 ) : $massvi3 = 0;
			( $day2stats->getTotalCount() > 0 ) ? $massvi2      = massvi( $day2 ) : $massvi2 = 0;
			( $day1stats->getTotalCount() > 0 ) ? $massvi1      = massvi( $day1 ) : $massvi1 = 0;
			( $TodayStats->getTotalCount() > 0 ) ? $massvitoday = massvi_today( $Today ) : $massvitoday = 0;



			$days_stats = [
				$d6,
				$d5,
				$d4,
				$d3,
				$d2,
				$d1,
				$today,
			];
			$day_max    = max( $days_stats );
			$day_max    = ( ceil( $day_max / 10000 ) ) * 10000;

			( $day6stats->getTotalCount() > 0 ) ? $d6_n = htmlchars( day_name( $day6 ) ) : $d6_n = countday( 6 );
			( $day5stats->getTotalCount() > 0 ) ? $d5_n = htmlchars( day_name( $day5 ) ) : $d5_n = countday( 5 );
			( $day4stats->getTotalCount() > 0 ) ? $d4_n = htmlchars( day_name( $day4 ) ) : $d4_n = countday( 4 );
			( $day3stats->getTotalCount() > 0 ) ? $d3_n = htmlchars( day_name( $day3 ) ) : $d3_n = countday( 3 );
			( $day2stats->getTotalCount() > 0 ) ? $d2_n = htmlchars( day_name( $day2 ) ) : $d2_n = countday( 2 );
			( $day1stats->getTotalCount() > 0 ) ? $d1_n = htmlchars( day_name( $day1 ) ) : $d1_n = countday( 1 );
			?>

		<script type="text/javascript" src="<?php echo APPURL . '/assets/js/plugins.js?v=' . VERSION; ?>"></script>
		<link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
		<style>.ct-series-a .ct-point, .ct-series-a .ct-line {stroke:#00a2ed}</style>
		<script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
		<script src="<?php echo PLUGINS_URL . '/' . $idname . '/assets/js/'; ?>chartist-plugin-pointlabels.min.js"></script>
		<?php require_once APPPATH . '/inc/js-locale.inc.php'; ?>
		<script type="text/javascript" src="<?php echo APPURL . '/assets/js/core.js?v=' . VERSION; ?>"></script>
		<script type="text/javascript" src="<?php echo PLUGINS_URL . '/' . $idname . '/assets/js/core.js?v=41' . VERSION; ?>"></script>
		<script type="text/javascript" charset="utf-8">

		$(function(){
			var data = [];
			var views = {labels: ['<?php echo $d6_n; ?>','<?php echo $d5_n; ?>','<?php echo $d4_n; ?>','<?php echo $d3_n; ?>','<?php echo $d2_n; ?>','<?php echo $d1_n; ?>','<?php echo __( 'Today' ); ?>'],series: [[<?php echo $d6; ?>,<?php echo $d5; ?>,<?php echo $d4; ?>,<?php echo $d3; ?>,<?php echo $d2; ?>,<?php echo $d1; ?>,<?php echo $today; ?>]]};
			var massvi = {labels: ['<?php echo $d6_n; ?>','<?php echo $d5_n; ?>','<?php echo $d4_n; ?>','<?php echo $d3_n; ?>','<?php echo $d2_n; ?>','<?php echo $d1_n; ?>','<?php echo __( 'Today' ); ?>'],series: [[<?php echo $massvi6; ?>,<?php echo $massvi5; ?>,<?php echo $massvi4; ?>,<?php echo $massvi3; ?>,<?php echo $massvi2; ?>,<?php echo $massvi1; ?>,<?php echo $massvitoday; ?>]]};
			var quiz = {labels: ['<?php echo $d6_n; ?>','<?php echo $d5_n; ?>','<?php echo $d4_n; ?>','<?php echo $d3_n; ?>','<?php echo $d2_n; ?>','<?php echo $d1_n; ?>','<?php echo __( 'Today' ); ?>'],series: [[<?php echo $quiz6; ?>,<?php echo $quiz5; ?>,<?php echo $quiz4; ?>,<?php echo $quiz3; ?>,<?php echo $quiz2; ?>,<?php echo $quiz1; ?>,<?php echo $quiztoday; ?>]]};
			var polls = {labels: ['<?php echo $d6_n; ?>','<?php echo $d5_n; ?>','<?php echo $d4_n; ?>','<?php echo $d3_n; ?>','<?php echo $d2_n; ?>','<?php echo $d1_n; ?>','<?php echo __( 'Today' ); ?>'],series: [[<?php echo $p6; ?>,<?php echo $p5; ?>,<?php echo $p4; ?>,<?php echo $p3; ?>,<?php echo $p2; ?>,<?php echo $p1; ?>,<?php echo $ptoday; ?>]]};
			var slider = {labels: ['<?php echo $d6_n; ?>','<?php echo $d5_n; ?>','<?php echo $d4_n; ?>','<?php echo $d3_n; ?>','<?php echo $d2_n; ?>','<?php echo $d1_n; ?>','<?php echo __( 'Today' ); ?>'],series: [[<?php echo $s6; ?>,<?php echo $s5; ?>,<?php echo $s4; ?>,<?php echo $s3; ?>,<?php echo $s2; ?>,<?php echo $s1; ?>,<?php echo $stoday; ?>]]};
			var ques = {labels: ['<?php echo $d6_n; ?>','<?php echo $d5_n; ?>','<?php echo $d4_n; ?>','<?php echo $d3_n; ?>','<?php echo $d2_n; ?>','<?php echo $d1_n; ?>','<?php echo __( 'Today' ); ?>'],series: [[<?php echo $ques6; ?>,<?php echo $ques5; ?>,<?php echo $ques4; ?>,<?php echo $ques3; ?>,<?php echo $ques2; ?>,<?php echo $ques1; ?>,<?php echo $questoday; ?>]]};
			new Chartist.Line('#chart-views', views, {plugins: [Chartist.plugins.ctPointLabels({textAnchor: 'middle'})]});
			<?php if ( $Schedule->get( 'is_mass_story_view_active' ) ) : ?>
			new Chartist.Line('#chart-stories', massvi, {plugins: [Chartist.plugins.ctPointLabels({textAnchor: 'middle'})]});
			<?php endif; ?>
			<?php if ( $Schedule->get( 'is_question_active' ) ) : ?>
			new Chartist.Line('#chart-questions', ques, {plugins: [Chartist.plugins.ctPointLabels({textAnchor: 'middle'})]});
			<?php endif; ?>
			<?php if ( $Schedule->get( 'is_poll_active' ) ) : ?>
			new Chartist.Line('#chart-polls', polls, {plugins: [Chartist.plugins.ctPointLabels({textAnchor: 'middle'})]});
			<?php endif; ?>
			<?php if ( $Schedule->get( 'is_slider_active' ) ) : ?>
			new Chartist.Line('#chart-slider', slider, {plugins: [Chartist.plugins.ctPointLabels({textAnchor: 'middle'})]});
			<?php endif; ?>
			<?php if ( $Schedule->get( 'is_quiz_active' ) ) : ?>
			new Chartist.Line('#chart-quiz', quiz, {plugins: [Chartist.plugins.ctPointLabels({textAnchor: 'middle'})]});
			<?php endif; ?>
		})


		</script>

		<!-- GOOGLE ANALYTICS -->
		<?php require_once APPPATH . '/views/fragments/google-analytics.fragment.php'; ?>
	</body>
</html>
