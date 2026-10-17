<?php
/**
 * Reusable FAQ content and idempotent starter blog content.
 *
 * @package ForgetFit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the gym FAQ library.
 *
 * @param string $group Optional group filter: visit or membership.
 * @return array<int,array<string,string>>
 */
function forgetfit_faq_items( $group = '' ) {
	$items = array(
		array(
			'group'    => 'visit',
			'question' => __( 'What should I bring?', 'forgetfit' ),
			'answer'   => __( 'Wear comfortable training clothes and clean athletic shoes. Bring a water bottle, a small towel, and photo identification for your first visit. If you already have a workout plan, bring it too; otherwise the team can help you choose a sensible starting point.', 'forgetfit' ),
		),
		array(
			'group'    => 'visit',
			'question' => __( 'Is the gym beginner-friendly?', 'forgetfit' ),
			'answer'   => __( 'Yes. You do not need to get fit before joining. New visitors can ask for an orientation, learn how the main equipment works, and choose scaled exercise options that match their current confidence and experience.', 'forgetfit' ),
		),
		array(
			'group'    => 'visit',
			'question' => __( 'Are changing rooms available?', 'forgetfit' ),
			'answer'   => __( 'Changing rooms are available for use before and after training. Locker and shower availability can vary, so contact the team before your visit if you need a specific facility or accessibility arrangement.', 'forgetfit' ),
		),
		array(
			'group'    => 'visit',
			'question' => __( 'How does the free pass work?', 'forgetfit' ),
			'answer'   => __( 'Complete the free-pass form with your preferred visit date and time. The request is not a confirmed booking until the team contacts you. Once confirmed, arrive a little early for a welcome, a short tour, and help choosing what to do during your visit.', 'forgetfit' ),
		),
		array(
			'group'    => 'membership',
			'question' => __( 'Can I pause my membership?', 'forgetfit' ),
			'answer'   => __( 'Pause options depend on your membership agreement and the reason for the request. Contact the team before your next billing date; they will explain any eligibility rules, duration limits, fees, and the date your membership will restart.', 'forgetfit' ),
		),
		array(
			'group'    => 'membership',
			'question' => __( 'What is the cancellation notice?', 'forgetfit' ),
			'answer'   => __( 'The required notice period is the one stated in your signed membership agreement. Send the request through the approved contact method before the next billing date and keep the confirmation. A cancellation is complete only when the gym confirms the effective date and any final payment.', 'forgetfit' ),
		),
	);

	if ( ! $group ) {
		return $items;
	}

	return array_values(
		array_filter(
			$items,
			static function ( $item ) use ( $group ) {
				return $group === $item['group'];
			}
		)
	);
}

/**
 * Return complete starter articles. These are inserted only once and are then
 * regular WordPress posts that editors can change freely.
 *
 * @return array<int,array<string,mixed>>
 */
function forgetfit_starter_blog_posts() {
	return array(
		array(
			'title'   => 'Your first week at the gym',
			'slug'    => 'your-first-week-at-the-gym',
			'excerpt' => 'A calm, practical plan for learning the space, choosing manageable sessions, and finishing your first week ready to return.',
			'offset'  => 0,
			'content' => <<<'HTML'
<p>Your first week at a gym is not a test of how fit you are. It is a chance to learn the space, meet the people who can help, and leave with enough energy to come back. A successful first week is built around confidence and consistency—not exhaustion.</p>

<h2>Before your first visit</h2>
<p>Choose comfortable clothes, clean athletic shoes, and a water bottle. Pack a small towel and photo identification if the club needs it for your pass. Confirm your visit time, how to enter, and whether you need a lock for day-use storage. Removing those small uncertainties makes the first session feel much easier.</p>

<h2>Give yourself three simple goals</h2>
<ul>
<li>Learn where the main training areas and facilities are.</li>
<li>Practise a few movements with a level of effort you can control.</li>
<li>Decide the day and time of your next visit before you leave.</li>
</ul>
<p>You do not need to try every machine. Ask for an orientation and focus on the equipment that fits the program you plan to follow.</p>

<h2>A useful first-session structure</h2>
<p>Start with five to ten minutes of easy movement while you settle in. Continue with a short, balanced session: one lower-body movement, one push, one pull, and a comfortable conditioning activity. A coach can show you suitable options and help adjust the equipment. Finish while your technique still feels steady.</p>

<h2>Plan the rest of the week</h2>
<p>Two or three manageable visits are more useful than one punishing workout. Leave at least a day between unfamiliar strength sessions, keep normal daily movement in your routine, and pay attention to sleep, meals, and hydration. Mild muscle soreness can happen when an activity is new. Sharp, sudden, or worsening pain is different—stop and speak with a qualified professional.</p>

<h2>Record one win</h2>
<p>Write down what you did and one thing that felt easier than expected. It might be asking a question, learning a machine, or simply arriving on time. That record gives you a starting point for next week and turns a vague intention into a repeatable routine.</p>

<p><strong>Your first-week checklist:</strong> complete an orientation, learn four useful movements, schedule the next visit, and ask for help before uncertainty becomes a reason to stay home.</p>
HTML,
		),
		array(
			'title'   => 'Strength training for beginners',
			'slug'    => 'strength-training-for-beginners',
			'excerpt' => 'Learn the basic movement patterns, how to choose an appropriate starting level, and how to make progress without rushing.',
			'offset'  => 7,
			'content' => <<<'HTML'
<p>Strength training is practice. The aim is to perform useful movements with control, recover from them, and gradually do a little more as your ability improves. You do not need complicated exercises or maximum effort to get started.</p>

<h2>Learn movement patterns, not a long exercise list</h2>
<p>A balanced beginner program usually includes a squat or leg press, a hip-dominant movement, a push, a pull, and a way to carry or brace. Machines, dumbbells, resistance bands, and bodyweight exercises can all work. The best version is the one you can perform comfortably and repeat with good technique.</p>

<h2>Choose a manageable starting level</h2>
<p>Use a load that lets you move smoothly and finish each set feeling that you could have completed a few more good repetitions. The final repetition should look similar to the first. If your range of motion shortens, your position changes, or you have to rush, reduce the load or choose an easier variation.</p>

<h2>Keep the first program simple</h2>
<p>Two full-body sessions each week can be a practical beginning. Use a small number of exercises, perform a few controlled sets of each, and rest long enough to repeat the movement well. Write down the exercise, setup, load, and repetitions so the next session begins with useful information rather than guesswork.</p>

<h2>Progress one thing at a time</h2>
<p>Progress might mean an extra repetition, a small increase in load, a more comfortable range of motion, or better control. You do not need to change every variable at once. When all planned repetitions feel stable across several sessions, make one modest adjustment and observe how you recover.</p>

<h2>Ask for feedback early</h2>
<p>A coach can help you set up a machine, understand effort, and choose alternatives for movements that do not feel right. Tell them about relevant injuries, health conditions, or concerns before training. Exercise should be challenging, but pain is not a goal. Seek appropriate medical advice when a symptom or condition requires it.</p>

<p>Consistency makes the basics powerful. Practise the same core movements long enough to learn them, keep a simple record, and give your body time to adapt.</p>
HTML,
		),
		array(
			'title'   => 'How to build a consistent routine',
			'slug'    => 'how-to-build-a-consistent-routine',
			'excerpt' => 'Turn good intentions into repeatable gym visits with a realistic schedule, fewer decisions, and a plan for imperfect weeks.',
			'offset'  => 14,
			'content' => <<<'HTML'
<p>A routine becomes consistent when it still works on an ordinary week. Motivation can help you begin, but a clear schedule, a manageable plan, and fewer obstacles are what help you continue.</p>

<h2>Set a minimum you can keep</h2>
<p>Start with the smallest schedule that can move you forward—often two planned sessions a week. Treat additional visits as a bonus. A realistic minimum builds trust in your plan and is easier to maintain through busy periods than an ambitious schedule that depends on perfect conditions.</p>

<h2>Give each session a home</h2>
<p>Choose specific days and time windows and add them to your calendar. Connect each visit to something that already happens, such as travelling home from work or finishing a morning school run. Decide in advance what will happen if the preferred time becomes unavailable.</p>

<h2>Reduce decisions before they appear</h2>
<ul>
<li>Pack your training clothes the night before.</li>
<li>Keep a short written program in your phone or notebook.</li>
<li>Use the same warm-up until there is a reason to change it.</li>
<li>Know the shorter version of your session for busy days.</li>
</ul>
<p>The goal is to make arriving easier. Once you are at the gym, the written plan tells you what comes next.</p>

<h2>Measure attendance and useful progress</h2>
<p>Track completed sessions first. Then record a few details that matter to your goal, such as exercises, repetitions, loads, or time. Daily performance naturally changes, so look for trends across several weeks rather than judging the routine from one difficult day.</p>

<h2>Use the next-visit rule</h2>
<p>Missing a session is normal. Do not try to punish yourself with extra work or wait for a new month to restart. Return at the next planned opportunity and continue with an appropriate session. Consistency is the skill of returning, not a record of never being interrupted.</p>

<h2>Review every four to six weeks</h2>
<p>Ask whether the schedule fits your life, whether the program matches your goal, and whether recovery is reasonable. Change only what is creating a real problem. A routine that feels almost boring can be valuable because it leaves room for gradual progress.</p>
HTML,
		),
		array(
			'title'   => 'What to expect from a coached session',
			'slug'    => 'what-to-expect-from-a-coached-session',
			'excerpt' => 'A clear walkthrough of the conversation, movement practice, feedback, and next steps in a useful coaching session.',
			'offset'  => 21,
			'content' => <<<'HTML'
<p>A coached session should give you more than a hard workout. It should help you understand what you are doing, why it fits your goal, and how to repeat the important parts safely and confidently.</p>

<h2>Before the session begins</h2>
<p>Your coach will usually ask about your training experience, goals, schedule, preferences, and relevant injuries or health considerations. Be specific and honest. “I want to feel comfortable training twice a week” is often more useful than a vague goal with no timeframe. This conversation helps the coach choose an appropriate session.</p>

<h2>A purposeful warm-up</h2>
<p>The opening movements prepare you for the work ahead and let the coach observe how you move. The warm-up may include easy conditioning, mobility, and lighter versions of the main exercises. It should have a clear connection to the session rather than simply making you tired.</p>

<h2>Coaching during the main work</h2>
<p>Expect demonstrations, setup guidance, and one or two clear cues at a time. You may be asked how difficult a set felt or where you felt the movement. The coach can adjust the load, range, pace, or exercise variation based on your response. A different option is not a failure; it is part of matching the session to the person.</p>

<h2>Communication is part of training</h2>
<p>Say when an instruction is unclear, a movement causes pain, or the planned effort feels too high or too low. Ask what the exercise is intended to develop and what good execution should feel like. Useful coaching is a conversation, not a performance where you have to pretend everything is fine.</p>

<h2>Finish with a next step</h2>
<p>At the end, the coach should summarise what went well and what to practise. You may receive a simple program, a recommended training frequency, or one technique focus for the next visit. Write down equipment settings and any exercise names you are likely to forget.</p>

<p>A productive coached session leaves you informed and capable. The best sign of quality is not how exhausted you feel; it is whether the session helped you take the next sensible step toward your goal.</p>
HTML,
		),
		array(
			'title'   => 'How gym orientation works',
			'slug'    => 'how-gym-orientation-works',
			'excerpt' => 'See what a practical gym orientation covers, which questions to ask, and how it helps you train independently.',
			'offset'  => 28,
			'content' => <<<'HTML'
<p>A gym orientation is a practical introduction to the building, the equipment, and the rules that keep the space working well. It is especially useful for first-time members, but anyone returning after a long break can benefit.</p>

<h2>Start with the essentials</h2>
<p>A team member should show you how to enter, where to store your belongings, how to find changing and accessible facilities, and who to speak to when you need help. You should also learn the emergency exits, reporting process, and any areas that require staff supervision.</p>

<h2>Understand the training zones</h2>
<p>The tour normally covers cardio equipment, resistance machines, free weights, functional training space, and stretching areas. Ask which zones are busiest at the time you expect to visit and where you can find alternatives when a preferred station is in use.</p>

<h2>Learn setup before intensity</h2>
<p>For the equipment you expect to use, learn how to adjust the seat, handles, safety stops, and starting position. Practise with a light, controlled effort. Save the settings in your phone so you can reproduce them. Staff do not need to demonstrate every machine—focus on the few that belong in your first program.</p>

<h2>Cover everyday gym etiquette</h2>
<ul>
<li>Return weights and small equipment after use.</li>
<li>Clean contact surfaces according to the club’s instructions.</li>
<li>Share popular equipment respectfully during busy periods.</li>
<li>Keep walkways clear and use safety equipment where required.</li>
</ul>

<h2>Leave with a simple plan</h2>
<p>Before the orientation ends, make sure you know what your first independent session will include, how long it should take, and where to ask for help. Confirm how classes or coached sessions are booked and whether advance registration is required.</p>

<p>Bring your questions. A good orientation is not meant to impress you with every feature; it is meant to remove uncertainty so your next visit feels familiar, safe, and useful.</p>
HTML,
		),
	);
}

/**
 * Seed the editorial starter set once without replacing existing content.
 */
function forgetfit_maybe_create_starter_blog_posts() {
	if ( '1' === get_option( 'forgetfit_starter_blog_v1' ) ) {
		return;
	}

	$lock_key = 'forgetfit_starter_blog_v1_lock';
	$lock     = (int) get_option( $lock_key );
	if ( $lock && $lock < time() - 5 * MINUTE_IN_SECONDS ) {
		delete_option( $lock_key );
	}
	if ( ! add_option( $lock_key, time(), '', false ) ) {
		return;
	}

	$term = term_exists( 'training-guides', 'category' );
	if ( ! $term ) {
		$term = wp_insert_term( __( 'Training Guides', 'forgetfit' ), 'category', array( 'slug' => 'training-guides' ) );
	}
	$category_id = is_wp_error( $term ) ? 0 : (int) ( is_array( $term ) ? $term['term_id'] : $term );
	$admins      = get_users( array( 'role' => 'administrator', 'number' => 1, 'fields' => 'ID' ) );
	$author_id   = $admins ? (int) $admins[0] : 1;
	$complete    = true;
	$now         = new DateTimeImmutable( 'now', wp_timezone() );

	foreach ( forgetfit_starter_blog_posts() as $article ) {
		$existing = get_posts(
			array(
				'name'             => $article['slug'],
				'post_type'        => 'post',
				'post_status'      => array( 'publish', 'future', 'draft', 'pending', 'private', 'trash' ),
				'numberposts'      => 1,
				'fields'           => 'ids',
				'suppress_filters' => true,
			)
		);
		if ( $existing ) {
			continue;
		}

		$post_date = $now->modify( '-' . absint( $article['offset'] ) . ' days' )->format( 'Y-m-d H:i:s' );
		$post_id   = wp_insert_post(
			array(
				'post_title'    => $article['title'],
				'post_name'     => $article['slug'],
				'post_status'   => 'publish',
				'post_type'     => 'post',
				'post_author'   => $author_id,
				'post_excerpt'  => $article['excerpt'],
				'post_content'  => wp_kses_post( $article['content'] ),
				'post_date'     => $post_date,
				'post_date_gmt' => get_gmt_from_date( $post_date ),
				'post_category' => $category_id ? array( $category_id ) : array(),
				'meta_input'    => array( '_forgetfit_starter_article' => '1' ),
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			$complete = false;
		}
	}

	if ( $complete ) {
		update_option( 'forgetfit_starter_blog_v1', '1' );
	}
	delete_option( $lock_key );
}
add_action( 'init', 'forgetfit_maybe_create_starter_blog_posts', 40 );
