<?php
/**
 * The template for displaying lading page.
 *
 * Template name: Landing Page V1
 *
 * @package electro
 */

remove_action( 'electro_content_top', 'electro_breadcrumb', 10 );
get_header('landing');
remove_action( 'electro_content_top', 'electro_site_content_inner_open', 10 );
$assetsPath = get_template_directory_uri().'/assets/images/landing';

?>
	<div class="row japee-landing-header">
		<div class="col-md-6 col-xs-12 header-content-area">
			<!-- <h1> Bridging Oceans <br>
				Bringing Japan Closer to You
			</h1>
			<div class="japee-visual-data">
				<img src="<?php // echo $assetsPath; ?>/top_01_en.png" alt="">
				<img src="<?php// echo $assetsPath; ?>/top_02_en.png" alt="">
			</div>
			<div class="app-store-links-container">
				<div class="app-store-smile">
					<img src="<?php // echo $assetsPath; ?>/app_icon.png" alt="">
				</div>
				<div class="app-store-links">
					<a href="#"> <img src="<?php // echo $assetsPath; ?>/btn_appStore.png"/></a>
					<a href="#"> <img src="<?php // echo $assetsPath; ?>/btn_googlePlay.png"/></a>
				</div>
			</div> -->
		</div>
	</div>
	<div class="landing-page-section container-fluid">
		<div class="section-header container">
			<span class="header-line"></span>
			<h1>Japanes Online Shoping Mall</h1>
		</div>
		<div class="container">
			<div class="col-md-3 col-sm-3 section-media">
				<img class="img img-responsive" src="<?php echo $assetsPath ?>/iconmonstr-japan.svg"/>
			</div>
			<div class="col-md-8 col-sm-8 col-sm-offset-0 col-md-offset-1 section-content">
				<h3 class="sub-header"> We spread Japanese culture to all over the world </h3>
				<p>JAPEE.TOKYO is an online shopping mall. We spread Japanese culture and delivery japanese product to all over the world</p>
				<p>We're offering the newest cosmetics, baby products, foods, and much more.</p>
				<p>"We believe Japan has one of the best Culture in the world and we believe we can spread Japanese Culture to all over the world. JAPEE.TOKYO made by the idea."</p>
				<p>We Welcome and encourage you to share our culture!</p>
			</div>
		</div>
	</div>

	<div class="landing-page-section container-fluid">
		<div class="section-header container">
			<div class="section-header-inner">
				<span class="header-line"></span>
				<h1>Safety and Convenient Shopping!</h1>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-sm-4 section-block">
					<div class="block-inner">
						<div class="block-image">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18.604 16.111c.018.276.048.531.092.758-.583.106-.606-.469-.092-.758zm-4.354.16h.996l-.498-1.43-.498 1.43zM17 10.166c3.697 0 7 2.465 7 5.837 0 1.196-.432 2.37-1.193 3.281-.033 1.068.596 2.6 1.162 3.715-1.518-.274-3.676-.88-4.654-1.48C13.928 22.832 10 19.535 10 16.003c0-3.39 3.326-5.837 7-5.837zm-.437 7.584l-1.44-3.792h-.76l-1.447 3.792h.812l.312-.875h1.422l.313.875h.788zm3.988-2.296l.079-.353-.642-.125-.064.292a2.739 2.739 0 0 0-.679-.012l.028-.458h.717v-.608h-.631l.084-.335-.627-.188a4.406 4.406 0 0 0-.123.523h-.715v.608h.638a8.45 8.45 0 0 0-.03.605c-.704.259-1.002.751-1.002 1.198 0 .528.416.992 1.074.932.817-.074 1.362-.691 1.682-1.45.332.19.471.511.346.807-.115.275-.455.536-1.104.523v.654c.721.011 1.429-.262 1.707-.923.27-.647-.041-1.352-.738-1.69zm-1.25 1.073a2.25 2.25 0 0 0 .407-.626 2.115 2.115 0 0 0-.462.012c.011.22.028.425.055.614zM8.809 19.23a8.303 8.303 0 0 1-4.827-2.478c-.182-.189-.315-.636-.019-.819l.302-.078c.235-.199-.221-1.009-.04-1.14.561-.401.295-.893-.051-1.299-.146-.172-.948-1-1.038-.853.067-.226-.146-.772-.274-.987-.217-.362-.502-.585-.617-.982-.046-.158-.046-.64-.139-.751-.038-.045-.327-.167-.317-.233a8.305 8.305 0 0 1 2.149-4.318l.746-.301c.468-.703.495-.158.916-.341.141 0 .301-.578.452-.667.261-.169.062-.169.013-.245-.104-.168 2.191-1.003 2.229-.853.032.127-1.135.734-1.007.716-.296.039-.352.571-.297.568.147-.005 1.074-.704 1.506-.555.423.146 1.183-.336 1.48-.582.149-.125.286-.344.483-.344.909 0 2.268.374 2.579.56.474.283-.204.263-.277.447a2.23 2.23 0 0 1-.249.412c-.102.133-.399.077-.341-.082.059-.167.348-.231-.086-.271-.445-.041-.568-.341-1.014.034-.134.113-.234.286-.321.433-.123.21-.42.201-.544.396-.12.192.07.512.304.371.062-.038.765.645.674.095-.059-.364.196-.612.201-.887.003-.184.28-.066.206.03-.097.121-.203.534.051.554.096.008.339-.158.425-.084-.096-.002-.315.383-.3.376-.108.048-.233-.021-.15.21.074.228-.408.201-.495.247-.037.02-.417-.114-.408-.028-.131-.109.037-.379-.072-.422-.11.168-.058.512-.294.512-.202 0-.482.229-.604.369-.087.097-.609.308-.666.302.299.031.286.25.261.437-.06.433-.995.032-.956.196.038.158-.107.586-.139.724-.029.125.402.205.378.267.002-.007.583-.199.64-.25l.131-.293a1.7 1.7 0 0 1 .35-.179l.149-.258c.05-.02.645-.112.686-.092.149.068.428.353.532.483.036.047.227.117.227.188.069.107-.051.148-.006.223.104.193.132-.401.087-.29 0-.189.142.071.174.049l-.657-.654c-.204-.342.543.183.64.247.096.063.285.629.537.501.158-.08-.004-.139.106-.229l.449-.09c-.357.261.279.602.182.556.16.074.254-.058.354-.021.057.022.663.015.566-.082.151.076.082.748-.044.851-.204.169-1.182.1-1.399-.057-.361-.262-.297.279-.473.352-.344.142-.857-.463-1.218-.482.176.026.015-.445.015-.478-.139-.171-1.02.019-1.252.05-.434.058-.89.052-1.223.324-.234.189-.237.5-.477.651-.156.095-.325.064-.456.189-.234.222-.504.552-.637.845-.054.123.072.416.041.574-.307.967.076 2.308 1.248 2.456.25.032.506.144.759.117 1.332-2.88 4.568-4.92 8.347-4.92.932 0 1.831.124 2.678.354C18.573 4.199 14.666 1 10 1 4.477 1 0 5.477 0 11s4.477 10 10 10l.074-.004a7.771 7.771 0 0 1-1.265-1.766zm4.778-13.035c.068-.008-.089.138-.089.138.027.212.183.327.479.435.36.129.03.375-.176.317-.114-.032-.704-.21-.725.021 0 .138-.56.001-.473-.145.061-.098.041-.319.152-.464.16-.21.313-.096.318.026.002.327.332-.306.514-.328zm-4.532-.292c.08.113.688-.163.591-.146.187-.094.024-.104-.082-.159-.035-.179-.065-.456-.177-.566l.074-.085c-.173-.249-.301.302-.301.302l.09-.026-.042.113c.071.129.019.207.007.277l-.124.077c-.045.055.215.062.217.071.009.028-.313.074-.253.142zm-.396-.286c-.069.071.002.116.073.085.104-.045.244-.044.26-.183l.066-.084c.029-.043-.057-.114-.092-.121l-.124.086-.061.016-.056.072.006.04-.072.089zm2.634 2.107c-.055 0-.369.029-.34.084.178.293.403-.076.34-.084z"/></svg>
						</div>
						<div class="block-content">
							<h4>Available in English</h4>
							<p>English and Japanese</p>
						</div>
					</div>
					<p class="block-footer-text"> We speak your language, so there's nothing worry about !</p>
				</div>
				<div class="col-md-4 col-sm-4 section-block">
					<div class="block-inner">
						<div class="block-image">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M16.7 17.9l-.3.2v-1.7l.3-.2v1.7zm2.8-3.4l-.3.2v1.7l.3-.2v-1.7zM23 6.9v11.5L13.2 24 1 17V5.5L10.8 0 23 6.9zM9.2 3.2l9 5.2 1.7-.9-9.1-5.2-1.6.9zm2.8 9.7L3 7.7v8.2l9 5.1v-8.1zm3-2.8L6.2 4.9 4.2 6l8.9 5.1c-.1.1 1.9-1 1.9-1zm6-1l-2 1.1V13l-3 1.7v-2.9l-2 1.1v8.4l7-4V9.1zm-4.9 7.4l-.3.2v1.7l.3-.2v-1.7zm1.4-.8l-.3.2v1.7l.3-.2v-1.7zm.6-.3l-.3.2v1.7l.3-.2v-1.7zm.6-.4l-.4.2v1.7l.4-.2V15z"/><path d="M2.7 12.8l2.7-.5c.6-.1 1-.2 1.1-.2L2.3 5.4c0-.1 0-.1.1-.1l8 8c.1.1 0 .2 0 .2l-2.4.3c-.7.1-1.2.2-1.3.2l4.7 6.6c0 .1 0 .1-.1.1L2.5 13c.1-.1.1-.2.2-.2z"/></svg>
						</div>
						<div class="block-content">
							<h4>Shopping Method</h4>
							<p>You can choose a shipping method that suits your budget.</p>
						</div>
					</div>
					<p class="block-footer-text"> You can choose shipping methods from variety of types. However our recommendation is E-packet light.</p>
				</div>
				<div class="col-md-4 col-sm-4 section-block">
					<div class="block-inner">
						<div class="block-image">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18 10V6A6 6 0 0 0 6 6v4H3v14h18V10h-3zM8 6c0-2.206 1.795-4 4-4s4 1.794 4 4v4H8V6zm11 16H5V12h14v10zm-7.737-2L8.42 17.244l1.173-1.173 1.67 1.583L14.826 14 16 15.173 11.263 20z"/></svg>
						</div>
						<div class="block-content">
							<h4>Security</h4>
							<p>Secure and easy payment System</p>
						</div>
					</div>
					<p class="block-footer-text"> We believe security is most important. We are offering Stripe Payment or Paypal</p>
				</div>
			</div>
		</div>
	</div>

	<div class="landing-page-section container-fluid">
		<div class="section-header container">
			<div class="section-header-inner">
				<span class="header-line"></span>
				<h1>Accepted Methods of Payment </h1>
			</div>
		</div>
		<p class="text-center"> We accept the following payment method. </p>
		<div class="payment-method-list container">
			<img class="paypal-logo" src="<?php echo $assetsPath?>/Credic-Logo-PayPal.png" alt="">
			<img class="visa-logo" src="<?php echo $assetsPath?>/Credic-Logo-visa.svg" alt="">
			<img class="master-logo" src="<?php echo $assetsPath?>/Credic-Logo-MasterCard.png" alt="">
			<img class="american-logo" src="<?php echo $assetsPath?>/Credic-Logo-AmericanExpress.png" alt="">
			<img class="jsb-logo" src="<?php echo $assetsPath?>/Credic-Logo-JCB.png" alt="">
			<img class="discover-logo" src="<?php echo $assetsPath?>/Credic-Logo-Discover.png" alt="">
			<img class="dinner-logo" src="<?php echo $assetsPath?>/Credic-Logo-Dinersclub.svg" alt="">
		</div>
	</div>

	<div class="landing-page-section container-fluid">
		<div class="section-header container">
			<div class="section-header-inner">
				<span class="header-line"></span>
				<h1>Safety and Convenient Shopping!</h1> 
			</div>
		</div>
		<div class="container sefty-convenient-section">
			<div class="row">
				<div class="col-md-3 col-sm-6 col-xs-12 section-block">
					<div class="block-inner">
						<div class="block-header"><h4>Step 1</h4></div>
						<div class="block-icon">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M17 12.645v-2.289c-1.17-.417-1.907-.533-2.28-1.431-.373-.9.07-1.512.6-2.625l-1.618-1.619c-1.105.525-1.723.974-2.626.6-.9-.374-1.017-1.117-1.431-2.281h-2.29c-.412 1.158-.53 1.907-1.431 2.28h-.001c-.9.374-1.51-.07-2.625-.6l-1.617 1.619c.527 1.11.973 1.724.6 2.625-.375.901-1.123 1.019-2.281 1.431v2.289c1.155.412 1.907.531 2.28 1.431.376.908-.081 1.534-.6 2.625l1.618 1.619c1.107-.525 1.724-.974 2.625-.6h.001c.9.373 1.018 1.118 1.431 2.28h2.289c.412-1.158.53-1.905 1.437-2.282h.001c.894-.372 1.501.071 2.619.602l1.618-1.619c-.525-1.107-.974-1.723-.601-2.625.374-.899 1.126-1.019 2.282-1.43zm-8.5 1.689c-1.564 0-2.833-1.269-2.833-2.834s1.269-2.834 2.833-2.834 2.833 1.269 2.833 2.834-1.269 2.834-2.833 2.834zm15.5 4.205v-1.077c-.55-.196-.897-.251-1.073-.673-.176-.424.033-.711.282-1.236l-.762-.762c-.52.248-.811.458-1.235.283-.424-.175-.479-.525-.674-1.073h-1.076c-.194.545-.25.897-.674 1.073-.424.176-.711-.033-1.235-.283l-.762.762c.248.523.458.812.282 1.236-.176.424-.528.479-1.073.673v1.077c.544.193.897.25 1.073.673.177.427-.038.722-.282 1.236l.762.762c.521-.248.812-.458 1.235-.283.424.175.479.526.674 1.073h1.076c.194-.545.25-.897.676-1.074h.001c.421-.175.706.034 1.232.284l.762-.762c-.247-.521-.458-.812-.282-1.235s.529-.481 1.073-.674zm-4 .794c-.736 0-1.333-.597-1.333-1.333s.597-1.333 1.333-1.333 1.333.597 1.333 1.333-.597 1.333-1.333 1.333z"/></svg>
						</div>
						<div class="block-content">
							<p>Select Language and make an account.</p>
						</div>
					</div>
					<p class="block-footer-text"> Select your currency. <br> We are offering 4 currencies but we can ship you all.</p>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 section-block">
					<div class="block-inner">
						<div class="block-header"><h4>Step 2</h4></div>
						<div class="block-icon">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M10 21.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5zm3.5-1.5c-.828 0-1.5.671-1.5 1.5s.672 1.5 1.5 1.5 1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm6.305-15l-3.432 12h-10.428l-3.777-9h-2.168l4.615 11h13.239l3.474-12h1.929l.743-2h-4.195zm-13.805-4c6.712 1.617 7 9 7 9h2l-4 4-4-4h2s.94-6.42-3-9z"/></svg>
						</div>
						<div class="block-content">
							<p>Add your favorite items as much as you want!</p>
						</div>
					</div>
					<p class="block-footer-text">Add your favorite items !! <br> you can simply clicking the "Add to Basket" button.</p>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 section-block">
					<div class="block-inner">
						<div class="block-header"><h4>Step 3</h4></div>
						<div class="block-icon">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 7.093v-5.093h-3v2.093l3 3zm4 5.907l-12-12-12 12h3v10h7v-5h4v5h7v-10h3zm-5 8h-3v-5h-8v5h-3v-10.26l7-6.912 7 6.99v10.182z"/></svg>
						</div>
						<div class="block-content">
							<p>Select shipping method and secure payment.</p>
						</div>
					</div>
					<p class="block-footer-text"> Check our shipping destination. <br> Your address is most important to delivery. Please check carefully.</p>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 section-block">
					<div class="block-inner">
						<div class="block-header"><h4>Step 4</h4></div>
						<div class="block-icon">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M13.838 8.076l-.171.098v.858l.171-.098v-.858zm1.412.054l-.171.098v-.859l.171-.098v.859zm-4.367-8.13l-4.883 2.758v5.753l6.125 3.489 4.875-2.775v-5.754l-6.117-3.471zm-.001 1.149l4.557 2.585-.851.458-4.511-2.589.805-.454zm.618 9.344l-4.5-2.563v-4.095l4.5 2.609v4.049zm.509-4.91l-4.43-2.569 1.022-.584 4.409 2.61-1.001.543zm3.991 3.06l-3.5 1.993v-4.181l1-.539v1.428l1.5-.844v-1.393l1-.539v4.075zm-2.453.458l-.175.098v-.858l.175-.099v.859zm.702-.401l-.172.098v-.859l.172-.098v.859zm.287-.163l-.171.098v-.859l.171-.098v.859zm.292-.166l-.175.099v-.859l.175-.099v.859zm5.172 5.004v-3.213c0-.77.506-1.162 1.008-1.162.498 0 .992.383.992 1.163v4.086c0 .796-.071 1.179-.573 2.092-.793 1.441-2.242 4.807-2.242 7.66h-5.002s-.559-2.759-.763-3.942c-.411-2.377.126-3.471 1.109-4.485 1.021-1.053 1.527-1.551 1.995-2.035 1.081-1.121 2.552.194 1.694 1.222-.468.561-1.624 1.803-1.901 2.171-.268.356.231.857.624.447.573-.599 1.905-2.083 2.365-2.618.443-.517.694-.829.694-1.386zm-16 0v-3.213c0-.77-.506-1.162-1.008-1.162-.498 0-.992.383-.992 1.163v4.086c0 .796.071 1.179.573 2.092.793 1.441 2.242 4.461 2.242 7.66h5.002s.559-2.759.763-3.942c.411-2.377-.126-3.471-1.109-4.485-1.021-1.053-1.527-1.551-1.995-2.035-1.081-1.121-2.552.194-1.694 1.222.468.561 1.624 1.803 1.901 2.171.268.356-.231.857-.624.447-.573-.599-1.905-2.083-2.365-2.618-.443-.517-.694-.829-.694-1.386z"/></svg>
						</div>
						<div class="block-content">
							<p>Recieve your parcel.</p>
						</div>
					</div>
					<p class="block-footer-text"> Thats all ! <br> We will ship you as soon as possible!</p>
				</div>
			</div>
		</div>
	</div>

	<div class="landing-page-section bg-dark section-full pt-30 pb-60">
		<div class="section-header container">
			<span class="header-line"></span>
			<h1 class="bg-dark">Our Promise</h1>
		</div>
		<div class="container bg-white">
			<div class="section-body-header">
				<h3 class="title-2"> Package Receive by Missing or Wrong Items </h3>
				<p>We will refund you part of shipping fees and product cost.</p>
				<p>You have feel free to contact us.</p>
			</div>
			<div class="missing-wrong-item-container">
				<div class="section-item flex-1">
					<img src="<?php echo $assetsPath?>/missing-item.png" alt="">
					<h4 class="item-title"> Missing Item </h4>
				</div>
				<div class="section-item flex-1">
					<img src="<?php echo $assetsPath?>/wrong-item.png" alt="">
					<h4 class="item-title"> Missing Item </h4>
				</div>
			</div>
		</div>
		<div class="container bg-white mt-30">
			<div class="section-body-header">
				<h3 class="title-2">Lost & Damage Percel</h3>
				<img src="<?php echo $assetsPath?>/Japan_Post_Office_Logo.svg.png" alt="">
				<p>We are shipping from Japan to overseas, so it happend sometime. But you don't need to worry about that. <br> Our shipping method has insurance for all products. You can get refund. Please contact to our customer support. 
				<br>Please note: damage and lost compensation insurance is only EMS, ePacket light. <span class="color-red">Small Packet shipments is not included.</span></p>
			</div>
			<div class="missing-wrong-item-container">
				<div class="section-item flex-1">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 183.372 200.279">
					<g id="グループ_14" data-name="グループ 14" transform="translate(-565 -4212.705)">
						<path id="パス_5" data-name="パス 5" d="M180.372,56.258V150.02l-79.9,45.658L1,138.606V44.843L80.9,0Z" transform="translate(566 4215)" fill="none" stroke="#ff90c8" stroke-width="4" stroke-dasharray="8 7"/>
						<path id="パス_10" data-name="パス 10" d="M1,135.985l100.276,60.174L180.2,149.306" transform="translate(566 4126)" fill="none" stroke="#ff90c8" stroke-width="4" stroke-dasharray="8 7"/>
						<path id="パス_10-2" data-name="パス 10" d="M1,135.985l98.007,60.01" transform="translate(599 4107)" fill="none" stroke="#ff90c8" stroke-width="4" stroke-dasharray="8 7"/>
						<path id="パス_12" data-name="パス 12" d="M1,135.985l98.007,60.01" transform="translate(619 4095)" fill="none" stroke="#ff90c8" stroke-width="4" stroke-dasharray="8 7"/>
						<path id="パス_11" data-name="パス 11" d="M665.1,4322.159v86.3" fill="none" stroke="#ff90c8" stroke-width="4" stroke-dasharray="8 7"/>
						<path id="パス_13" data-name="パス 13" d="M665.1,4322.159v34.7" transform="translate(33 -19)" fill="none" stroke="#ff90c8" stroke-width="4" stroke-dasharray="8 7"/>
						<path id="パス_14" data-name="パス 14" d="M665.1,4322.159v34.7" transform="translate(51 -30)" fill="none" stroke="#ff90c8" stroke-width="4" stroke-dasharray="8 7"/>
						<path id="パス_15" data-name="パス 15" d="M697.916,4337.407l18.959-11.55" fill="none" stroke="#ff90c8" stroke-width="4" stroke-dasharray="7 8"/>
					</g>
				</svg>


					<h4 class="item-title"> Missing Parcel </h4>
				</div>
				<div class="section-item flex-1">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M16.7 17.9l-.3.2v-1.7l.3-.2v1.7zm2.8-3.4l-.3.2v1.7l.3-.2v-1.7zM23 6.9v11.5L13.2 24 1 17V5.5L10.8 0 23 6.9zM9.2 3.2l9 5.2 1.7-.9-9.1-5.2-1.6.9zm2.8 9.7L3 7.7v8.2l9 5.1v-8.1zm3-2.8L6.2 4.9 4.2 6l8.9 5.1c-.1.1 1.9-1 1.9-1zm6-1l-2 1.1V13l-3 1.7v-2.9l-2 1.1v8.4l7-4V9.1zm-4.9 7.4l-.3.2v1.7l.3-.2v-1.7zm1.4-.8l-.3.2v1.7l.3-.2v-1.7zm.6-.3l-.3.2v1.7l.3-.2v-1.7zm.6-.4l-.4.2v1.7l.4-.2V15z"/><path d="M2.7 12.8l2.7-.5c.6-.1 1-.2 1.1-.2L2.3 5.4c0-.1 0-.1.1-.1l8 8c.1.1 0 .2 0 .2l-2.4.3c-.7.1-1.2.2-1.3.2l4.7 6.6c0 .1 0 .1-.1.1L2.5 13c.1-.1.1-.2.2-.2z"/></svg>
					<h4 class="item-title"> Damage Parcel </h4>
				</div>
			</div>
			<div class="more-details-button">
				<a href="https://www.post.japanpost.jp/int/service/damage_en.html" class="btn btn-default btn-black"> View Details</a>
			</div>
		</div>

		<div class="container bg-white mt-30">
			<div class="section-body-header">
				<h3 class="title-2">When a Customer Take Out Containers</h3>
			</div>
			<div class="missing-wrong-item-container takout-part">
				<div class="section-item">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M16.143 2l5.857 5.858v8.284l-5.857 5.858h-8.286l-5.857-5.858v-8.284l5.857-5.858h8.286zm.828-2h-9.942l-7.029 7.029v9.941l7.029 7.03h9.941l7.03-7.029v-9.942l-7.029-7.029zm.932 11.667c-.127.328-1.695 3.888-2.096 4.786-.42.941-1.239 1.881-2.751 1.881h-2.627c-1.592 0-2.43-.945-2.43-2.596v-7.208c0-.956 1.316-.908 1.316-.044v3.16c0 .26.478.259.478 0v-5.079c0-.982 1.472-.957 1.472 0v4.795c0 .264.443.252.443-.005v-5.628c0-.957 1.457-.984 1.457 0l.001 5.692c0 .254.459.261.459 0v-4.78c0-.905 1.596-.933 1.596 0v5.417c0 .331.327.384.45.131.118-.24.605-1.315.613-1.327.49-1.029 2.128-.404 1.619.805z"/></svg>
					<h4 class="item-title">Prohobited Items</h4>
				</div>
				<div class="section-item flex-1">
					<p>Customer has to take a responsibilty for paying import taxes. This taxes imposed by your country laws.</p>
					<br>
					<p>When a customer didn't accept the item in your country. JAPEE.TOKYO will refund you for the product costs. Please send customer support to Written notification letter or some other proof document.</p>
				</div>
			</div>
		</div>

		<div class="container bg-white mt-30">
			<div class="section-body-header">
				<h3 class="title-2">If You Have Any Trouble or Question</h3>
				<p>We have a Customer Support. You can feel free to contact us. <br> We will answer you as soon as possible.</p>
			</div>
			<div class="more-details-button">
				<a href="https://japee.tokyo/contact-v1/" class="btn btn-default btn-black">Contact Us</a>
			</div>
		</div>

	</div>
	<hr>
	<div class="landing-page-section section-full">
		<div class="section-header container">
			<span class="header-line"></span>
			<h1>FAQ</h1>
		</div>
		<div class="faq-container container">
			<div class="faq-part">
				<div class="faq-row row faq-question">
					<div class="faq-pointer"> <span class="faq-mark"> Q </span></div>
					<div class="faq-details"> <p>Can I make order at JAPEE.TOKYO from my smartphone ?</p> </div>
				</div>
				<div class="faq-row row faq-answer">
					<div class="faq-pointer"> <span class="faq-mark"> A </span></div>
					<div class="faq-details"> <p>You can make order on any devices via the internet or Android apps, iPhone iOS apps. <br>
						Click here to download the JAPEE.TOKYO Appp. (<a href="#">iPhone AppStore</a> <a href="#">Android App PlayStore</a>)</p> </div>
				</div>
			</div>
			<div class="faq-part">
				<div class="faq-row row faq-question">
					<div class="faq-pointer"> <span class="faq-mark"> Q </span></div>
					<div class="faq-details"> <p>Can you ship to my country?</p> </div>
				</div>
				<div class="faq-row row faq-answer">
					<div class="faq-pointer"> <span class="faq-mark"> A </span></div>
					<div class="faq-details"> <p>Yes we can ship you all over the world. You can feel free to shop any product.</p> </div>
				</div>
			</div>
			<div class="faq-part">
				<div class="faq-row row faq-question">
					<div class="faq-pointer"> <span class="faq-mark"> Q </span></div>
					<div class="faq-details"> <p>How much is shipping fee to my country?</p> </div>
				</div>
				<div class="faq-row row faq-answer">
					<div class="faq-pointer"> <span class="faq-mark"> A </span></div>
					<div class="faq-details"> <p>Our shipping fees is depend on the your order products weight and your select shipping method. <br> You can select shipping method after cart box. (Of course before payment)</p> </div>
				</div>
			</div>
		</div>
	</div>
	<div class="landing-page-section section-full landing-page-footer bg-dark">
		<h3 class="footer-title text-center color-pink">Start Shopping With JAPEE.TOKYO</h3>
		<div class="more-details-button">
				<a href="https://japee.tokyo/my-account/" class="btn btn-default btn-white">Create an Account</a>
		</div>
	</div>
<?php 

get_footer( 'blank' );