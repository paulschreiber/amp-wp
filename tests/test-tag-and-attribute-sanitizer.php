<?php

class AMP_Tag_And_Attribute_Sanitizer_Test extends WP_UnitTestCase {

	protected $allowed_tags;
	protected $globally_allowed_attrs;
	protected $layout_allowed_attrs;

	public function get_data() {
		return array(
			'empty_doc' => array(
				'',
				'',
			),

			'a-test'                                                    => array(
				'<a on="tap:see-image-lightbox" role="button" class="button button-secondary play" tabindex="0">Show Image</a>',
			),

			'a4a'                                                       => array(
				'<amp-ad width="300" height="400" type="fake" data-use-a4a="true" data-vars-analytics-var="bar" src="fake_amp.json"><div placeholder=""></div><div fallback=""></div></amp-ad>',
			),

			'ads'                                                       => array(
				'<amp-ad width="300" height="250" type="a9" data-aax_size="300x250" data-aax_pubname="test123" data-aax_src="302"><div placeholder=""></div><div fallback=""></div></amp-ad>',
			),

			'adsense'                                                   => array(
				'<amp-ad width="300" height="250" type="adsense" data-ad-client="ca-pub-2005682797531342" data-ad-slot="7046626912"><div placeholder=""></div><div fallback=""></div></amp-ad>',
			),

			'amp-3q-player'                                             => array(
				'<amp-3q-player data-id="c8dbe7f4-7f7f-11e6-a407-0cc47a188158" layout="responsive" width="480" height="270"></amp-3q-player>',
			),

			'amp-ad'                                                    => array(
				'<amp-ad width="300" height="250" type="foo"></amp-ad>',
			),

			'amp-ad-exit'                                               => array(
				'<amp-ad-exit id="exit-api"><script type="application/json"></script></amp-ad-exit>',
			),

			'amp-animation'                                             => array(
				'<amp-animation layout="nodisplay"><script type="application/json"></script></amp-animation>',
			),

			'amp-call-tracking'                                         => array(
				'<amp-call-tracking config="https://example.com/calltracking.json"><a href="tel:123456789">+1 (23) 456-789</a></amp-call-tracking>',
			),

			'amp-call-tracking_blacklisted_config'                      => array(
				'<amp-call-tracking config="__amp_source_origin"><a href="tel:123456789">+1 (23) 456-789</a></amp-call-tracking>',
				'',
			),

			'amp-embed'                                                 => array(
				'<amp-embed type="taboola" width="400" height="300" layout="responsive"></amp-embed>',
			),

			'amp-facebook-comments'                                     => array(
				'<amp-facebook-comments width="486" height="657" data-href="http://example.com/baz" layout="responsive" data-numposts="5"></amp-facebook-comments>',
			),

			'amp-facebook-comments_missing_required_attribute'          => array(
				'<amp-facebook-comments width="486" height="657" layout="responsive" data-numposts="5"></amp-facebook-comments>',
				'',
			),

			'amp-facebook-like'                                         => array(
				'<amp-facebook-like width="90" height="20" data-href="http://example.com/baz" layout="fixed" data-layout="button_count"></amp-facebook-like>',
			),

			'amp-facebook-like_missing_required_attribute'              => array(
				'<amp-facebook-like width="90" height="20" layout="fixed" data-layout="button_count"></amp-facebook-like>',
				'',
			),

			'amp-fit-text'                                              => array(
				'<amp-fit-text width="300" height="200" layout="responsive">Lorem ipsum</amp-fit-text>',
			),

			'amp-gist'                                                  => array(
				'<amp-gist layout="fixed-height" data-gistid="a19" height="1613"></amp-gist>',
			),

			'amp-gist_missing_mandatory_attribute'                      => array(
				'<amp-gist layout="fixed-height" height="1613"></amp-gist>',
				'',
			),

			'amp-gwd-animation'                                         => array(
				'<amp-gwd-animation id="4321" layout="nodisplay"></amp-gwd-animation>',
			),

			'amp-iframe'                                                => array(
				'<amp-iframe width="600" height="200" sandbox="allow-scripts allow-same-origin" layout="responsive" frameborder="0" src="https://www.example.com"></amp-iframe>',
			),

			'amp-iframe_incorrect_protocol'                             => array(
				'<amp-iframe width="600" height="200" sandbox="allow-scripts allow-same-origin" layout="responsive" frameborder="0" src="masterprotocol://www.example.com"></amp-iframe>',
				'<amp-iframe width="600" height="200" sandbox="allow-scripts allow-same-origin" layout="responsive" frameborder="0"></amp-iframe>',
			),

			'amp-ima-video'                                             => array(
				'<amp-ima-video width="640" height="360" data-tag="https://example.com/foo" layout="responsive" data-src="https://example.com/bar"></amp-ima-video>',
			),

			'amp-ima-video_missing_required_attribute'                  => array(
				'<amp-ima-video width="640" height="360" layout="responsive" data-src="https://example.com/bar"></amp-ima-video>',
				'',
			),

			'amp-imgur'                                                 => array(
				'<amp-imgur data-imgur-id="54321" layout="responsive" width="540" height="663"></amp-imgur>',
			),

			'amp-install-serviceworker'                                 => array(
				'<amp-install-serviceworker src="https://www.emample.com/worker.js" data-iframe-src="https://www.example.com/serviceworker.html" layout="nodisplay"></amp-install-serviceworker>',
			),

			'amp-izlesene'                                              => array(
				'<amp-izlesene data-videoid="4321" layout="responsive" width="432" height="123"></amp-izlesene>',
			),

			'amp-nexxtv-player'                                         => array(
				'<amp-nexxtv-player data-mediaid="123ABC" data-client="4321"></amp-nexxtv-player>',
			),

			'amp-playbuzz'                                              => array(
				'<amp-playbuzz src="id-from-the-content-here" height="500" data-item-info="true" data-share-buttons="true" data-comments="true"></amp-playbuzz>',
			),

			'amp-playbuzz_no_src'                                       => array(
				'<amp-playbuzz height="500" data-item-info="true"></amp-playbuzz>',
			),

			'amp-position-observer'                                     => array(
				'<amp-position-observer intersection-ratios="1"></amp-position-observer>',
			),

			'amp-twitter'                                               => array(
				'<amp-twitter width="321" height="543" layout="responsive" data-tweetid="98765"></amp-twitter>',
			),

			'amp-user-notification'                                     => array(
				'<amp-user-notification layout="nodisplay" id="amp-user-notification1" data-show-if-href="https://example.com/api/show?timestamp=TIMESTAMP" data-dismiss-href="https://example.com/api/echo/post">This site uses cookies to personalize content.<a class="btn" on="tap:amp-user-notification1.dismiss">I accept</a></amp-user-notification>',
				'<amp-user-notification layout="nodisplay" id="amp-user-notification1" data-show-if-href="https://example.com/api/show?timestamp=TIMESTAMP" data-dismiss-href="https://example.com/api/echo/post">This site uses cookies to personalize content.<a class="btn" on="tap:amp-user-notification1.dismiss">I accept</a></amp-user-notification>',
			),

			'amp-video'                                                 => array(
				'<amp-video width="432" height="987" src="/video/location.mp4"></amp-video>',
			),

			'amp-vk'                                                    => array(
				'<amp-vk width="500" height="300" data-embedtype="post" layout="responsive"></amp-vk>',
			),

			'amp-apester-media'                                         => array(
				'<amp-apester-media height="444" data-apester-media-id="57a336dba187a2ca3005e826" layout="fixed-height"></amp-apester-media>',
				'<amp-apester-media height="444" data-apester-media-id="57a336dba187a2ca3005e826" layout="fixed-height"></amp-apester-media>',
			),

			'button'                                                    => array(
				'<button on="tap:AMP.setState(foo=\'foo\', isButtonDisabled=true, textClass=\'redBackground\', imgSrc=\'https://ampbyexample.com/img/Shetland_Sheepdog.jpg\', imgSize=200, imgAlt=\'Sheepdog\', videoSrc=\'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4\')">Click me</button>',
				'<button on="tap:AMP.setState(foo=\'foo\', isButtonDisabled=true, textClass=\'redBackground\', imgSrc=\'https://ampbyexample.com/img/Shetland_Sheepdog.jpg\', imgSize=200, imgAlt=\'Sheepdog\', videoSrc=\'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4\')">Click me</button>',
			),

			'brid-player'                                               => array(
				'<amp-brid-player data-partner="264" data-player="4144" data-video="13663" layout="responsive" width="480" height="270"></amp-brid-player>',
				'<amp-brid-player data-partner="264" data-player="4144" data-video="13663" layout="responsive" width="480" height="270"></amp-brid-player>',
			),

			'brightcove'                                                => array(
				'<amp-brightcove data-account="906043040001" data-video-id="1401169490001" data-player="180a5658-8be8-4f33-8eba-d562ab41b40c" layout="responsive" width="480" height="270"></amp-brightcove>',
				'<amp-brightcove data-account="906043040001" data-video-id="1401169490001" data-player="180a5658-8be8-4f33-8eba-d562ab41b40c" layout="responsive" width="480" height="270"></amp-brightcove>',
			),

			'carousel'                                                  => array(
				'<amp-carousel width="400" height="300" layout="responsive" type="slides" controls=""><div>hello world</div><amp-img src="https://lh3.googleusercontent.com/pSECrJ82R7-AqeBCOEPGPM9iG9OEIQ_QXcbubWIOdkY=w400-h300-no-n" layout="fill"></amp-img><amp-img src="https://lh3.googleusercontent.com/5rcQ32ml8E5ONp9f9-Rf78IofLb9QjS5_0mqsY1zEFc=w400-h300-no-n" width="400" height="300" layout="responsive"></amp-img><amp-img src="https://lh3.googleusercontent.com/Z4gtm5Bkxyv21Z2PtbTf95Clb9AE4VTR6olbBKYrenM=w400-h300-no-n" width="400" height="300" layout="responsive"></amp-img><amp-soundcloud height="300" layout="fixed-height" data-trackid="243169232"></amp-soundcloud><amp-youtube data-videoid="mGENRKrdoGY" width="400" height="300"></amp-youtube><amp-anim src="https://lh3.googleusercontent.com/qNn8GDz8Jfd-s9lt3Nc4lJeLjVyEaqGJTk1vuCUWazCmAeOBVjSWDD0SMTU7x0zhVe5UzOTKR0n-kN4SXx7yElvpKYvCMaRyS_g-jydhJ_cEVYmYPiZ_j1Y9de43mlKxU6s06uK1NAlpbSkL_046amEKOdgIACICkuWfOBwlw2hUDfjPOWskeyMrcTu8XOEerCLuVqXugG31QC345hz3lUyOlkdT9fMYVUynSERGNzHba7bXMOxKRe3izS5DIWUgJs3oeKYqA-V8iqgCvneD1jj0Ff68V_ajm4BDchQubBJU0ytXVkoWh27ngeEHubpnApOS6fcGsjPxeuMjnzAUtoTsiXz2FZi1mMrxrblJ-kZoAq1DJ95cnoqoa2CYq3BTgq2E8BRe2paNxLJ5GXBCTpNdXMpVJc6eD7ceijQyn-2qanilX-iK3ChbOq0uBHMvsdoC_LsFOu5KzbbNH71vM3DPkvDGmHJmF67Vj8sQ6uBrLnzpYlCyN4-Y9frR8zugDcqX5Q=w400-h300-no" width="400" height="300"><amp-img placeholder="" src="https://lh3.googleusercontent.com/qNn8GDz8Jfd-s9lt3Nc4lJeLjVyEaqGJTk1vuCUWazCmAeOBVjSWDD0SMTU7x0zhVe5UzOTKR0n-kN4SXx7yElvpKYvCMaRyS_g-jydhJ_cEVYmYPiZ_j1Y9de43mlKxU6s06uK1NAlpbSkL_046amEKOdgIACICkuWfOBwlw2hUDfjPOWskeyMrcTu8XOEerCLuVqXugG31QC345hz3lUyOlkdT9fMYVUynSERGNzHba7bXMOxKRe3izS5DIWUgJs3oeKYqA-V8iqgCvneD1jj0Ff68V_ajm4BDchQubBJU0ytXVkoWh27ngeEHubpnApOS6fcGsjPxeuMjnzAUtoTsiXz2FZi1mMrxrblJ-kZoAq1DJ95cnoqoa2CYq3BTgq2E8BRe2paNxLJ5GXBCTpNdXMpVJc6eD7ceijQyn-2qanilX-iK3ChbOq0uBHMvsdoC_LsFOu5KzbbNH71vM3DPkvDGmHJmF67Vj8sQ6uBrLnzpYlCyN4-Y9frR8zugDcqX5Q=w400-h300-no-k" width="400" height="300"></amp-img></amp-anim><amp-audio src="https://ia801402.us.archive.org/16/items/EDIS-SRP-0197-06/EDIS-SRP-0197-06.mp3"></amp-audio><amp-brightcove data-account="906043040001" data-video-id="1401169490001" data-player="180a5658-8be8-4f33-8eba-d562ab41b40c" layout="responsive" width="480" height="270"></amp-brightcove><amp-vimeo data-videoid="27246366" width="500" height="281"></amp-vimeo><amp-dailymotion data-videoid="x3rdtfy" width="500" height="281"></amp-dailymotion><amp-vine data-vineid="MdKjXez002d" width="381" height="381" layout="responsive"></amp-vine><amp-video src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4" width="358" height="204" layout="responsive" controls=""></amp-video></amp-carousel><amp-carousel width="auto" height="300" controls=""><div>hello world</div><amp-img src="https://lh3.googleusercontent.com/pSECrJ82R7-AqeBCOEPGPM9iG9OEIQ_QXcbubWIOdkY=w400-h300-no-n" width="400" height="300"></amp-img><amp-img src="https://lh3.googleusercontent.com/5rcQ32ml8E5ONp9f9-Rf78IofLb9QjS5_0mqsY1zEFc=w400-h300-no-n" width="400" height="300"></amp-img><amp-img src="https://lh3.googleusercontent.com/Z4gtm5Bkxyv21Z2PtbTf95Clb9AE4VTR6olbBKYrenM=w400-h300-no-n" width="400" height="300"></amp-img><amp-soundcloud height="300" layout="fixed-height" data-trackid="243169232"></amp-soundcloud><amp-youtube data-videoid="mGENRKrdoGY" width="400" height="300"></amp-youtube><amp-anim src="https://lh3.googleusercontent.com/qNn8GDz8Jfd-s9lt3Nc4lJeLjVyEaqGJTk1vuCUWazCmAeOBVjSWDD0SMTU7x0zhVe5UzOTKR0n-kN4SXx7yElvpKYvCMaRyS_g-jydhJ_cEVYmYPiZ_j1Y9de43mlKxU6s06uK1NAlpbSkL_046amEKOdgIACICkuWfOBwlw2hUDfjPOWskeyMrcTu8XOEerCLuVqXugG31QC345hz3lUyOlkdT9fMYVUynSERGNzHba7bXMOxKRe3izS5DIWUgJs3oeKYqA-V8iqgCvneD1jj0Ff68V_ajm4BDchQubBJU0ytXVkoWh27ngeEHubpnApOS6fcGsjPxeuMjnzAUtoTsiXz2FZi1mMrxrblJ-kZoAq1DJ95cnoqoa2CYq3BTgq2E8BRe2paNxLJ5GXBCTpNdXMpVJc6eD7ceijQyn-2qanilX-iK3ChbOq0uBHMvsdoC_LsFOu5KzbbNH71vM3DPkvDGmHJmF67Vj8sQ6uBrLnzpYlCyN4-Y9frR8zugDcqX5Q=w400-h300-no" width="400" height="300"><amp-img placeholder="" src="https://lh3.googleusercontent.com/qNn8GDz8Jfd-s9lt3Nc4lJeLjVyEaqGJTk1vuCUWazCmAeOBVjSWDD0SMTU7x0zhVe5UzOTKR0n-kN4SXx7yElvpKYvCMaRyS_g-jydhJ_cEVYmYPiZ_j1Y9de43mlKxU6s06uK1NAlpbSkL_046amEKOdgIACICkuWfOBwlw2hUDfjPOWskeyMrcTu8XOEerCLuVqXugG31QC345hz3lUyOlkdT9fMYVUynSERGNzHba7bXMOxKRe3izS5DIWUgJs3oeKYqA-V8iqgCvneD1jj0Ff68V_ajm4BDchQubBJU0ytXVkoWh27ngeEHubpnApOS6fcGsjPxeuMjnzAUtoTsiXz2FZi1mMrxrblJ-kZoAq1DJ95cnoqoa2CYq3BTgq2E8BRe2paNxLJ5GXBCTpNdXMpVJc6eD7ceijQyn-2qanilX-iK3ChbOq0uBHMvsdoC_LsFOu5KzbbNH71vM3DPkvDGmHJmF67Vj8sQ6uBrLnzpYlCyN4-Y9frR8zugDcqX5Q=w400-h300-no-k" width="400" height="300"></amp-img></amp-anim><amp-audio src="https://ia801402.us.archive.org/16/items/EDIS-SRP-0197-06/EDIS-SRP-0197-06.mp3"></amp-audio><amp-brightcove data-account="906043040001" data-video-id="1401169490001" data-player="180a5658-8be8-4f33-8eba-d562ab41b40c" layout="responsive" width="300" height="300"></amp-brightcove><amp-vimeo data-videoid="27246366" width="300" height="300"></amp-vimeo><amp-dailymotion data-videoid="x3rdtfy" width="300" height="300"></amp-dailymotion><amp-vine data-vineid="MdKjXez002d" width="300" height="300"></amp-vine><amp-video src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4" width="300" height="300" controls=""></amp-video></amp-carousel>',
				'<amp-carousel width="400" height="300" layout="responsive" type="slides" controls=""><div>hello world</div><amp-img src="https://lh3.googleusercontent.com/pSECrJ82R7-AqeBCOEPGPM9iG9OEIQ_QXcbubWIOdkY=w400-h300-no-n" layout="fill"></amp-img><amp-img src="https://lh3.googleusercontent.com/5rcQ32ml8E5ONp9f9-Rf78IofLb9QjS5_0mqsY1zEFc=w400-h300-no-n" width="400" height="300" layout="responsive"></amp-img><amp-img src="https://lh3.googleusercontent.com/Z4gtm5Bkxyv21Z2PtbTf95Clb9AE4VTR6olbBKYrenM=w400-h300-no-n" width="400" height="300" layout="responsive"></amp-img><amp-soundcloud height="300" layout="fixed-height" data-trackid="243169232"></amp-soundcloud><amp-youtube data-videoid="mGENRKrdoGY" width="400" height="300"></amp-youtube><amp-anim src="https://lh3.googleusercontent.com/qNn8GDz8Jfd-s9lt3Nc4lJeLjVyEaqGJTk1vuCUWazCmAeOBVjSWDD0SMTU7x0zhVe5UzOTKR0n-kN4SXx7yElvpKYvCMaRyS_g-jydhJ_cEVYmYPiZ_j1Y9de43mlKxU6s06uK1NAlpbSkL_046amEKOdgIACICkuWfOBwlw2hUDfjPOWskeyMrcTu8XOEerCLuVqXugG31QC345hz3lUyOlkdT9fMYVUynSERGNzHba7bXMOxKRe3izS5DIWUgJs3oeKYqA-V8iqgCvneD1jj0Ff68V_ajm4BDchQubBJU0ytXVkoWh27ngeEHubpnApOS6fcGsjPxeuMjnzAUtoTsiXz2FZi1mMrxrblJ-kZoAq1DJ95cnoqoa2CYq3BTgq2E8BRe2paNxLJ5GXBCTpNdXMpVJc6eD7ceijQyn-2qanilX-iK3ChbOq0uBHMvsdoC_LsFOu5KzbbNH71vM3DPkvDGmHJmF67Vj8sQ6uBrLnzpYlCyN4-Y9frR8zugDcqX5Q=w400-h300-no" width="400" height="300"><amp-img placeholder="" src="https://lh3.googleusercontent.com/qNn8GDz8Jfd-s9lt3Nc4lJeLjVyEaqGJTk1vuCUWazCmAeOBVjSWDD0SMTU7x0zhVe5UzOTKR0n-kN4SXx7yElvpKYvCMaRyS_g-jydhJ_cEVYmYPiZ_j1Y9de43mlKxU6s06uK1NAlpbSkL_046amEKOdgIACICkuWfOBwlw2hUDfjPOWskeyMrcTu8XOEerCLuVqXugG31QC345hz3lUyOlkdT9fMYVUynSERGNzHba7bXMOxKRe3izS5DIWUgJs3oeKYqA-V8iqgCvneD1jj0Ff68V_ajm4BDchQubBJU0ytXVkoWh27ngeEHubpnApOS6fcGsjPxeuMjnzAUtoTsiXz2FZi1mMrxrblJ-kZoAq1DJ95cnoqoa2CYq3BTgq2E8BRe2paNxLJ5GXBCTpNdXMpVJc6eD7ceijQyn-2qanilX-iK3ChbOq0uBHMvsdoC_LsFOu5KzbbNH71vM3DPkvDGmHJmF67Vj8sQ6uBrLnzpYlCyN4-Y9frR8zugDcqX5Q=w400-h300-no-k" width="400" height="300"></amp-img></amp-anim><amp-audio src="https://ia801402.us.archive.org/16/items/EDIS-SRP-0197-06/EDIS-SRP-0197-06.mp3"></amp-audio><amp-brightcove data-account="906043040001" data-video-id="1401169490001" data-player="180a5658-8be8-4f33-8eba-d562ab41b40c" layout="responsive" width="480" height="270"></amp-brightcove><amp-vimeo data-videoid="27246366" width="500" height="281"></amp-vimeo><amp-dailymotion data-videoid="x3rdtfy" width="500" height="281"></amp-dailymotion><amp-vine data-vineid="MdKjXez002d" width="381" height="381" layout="responsive"></amp-vine><amp-video src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4" width="358" height="204" layout="responsive" controls=""></amp-video></amp-carousel><amp-carousel width="auto" height="300" controls=""><div>hello world</div><amp-img src="https://lh3.googleusercontent.com/pSECrJ82R7-AqeBCOEPGPM9iG9OEIQ_QXcbubWIOdkY=w400-h300-no-n" width="400" height="300"></amp-img><amp-img src="https://lh3.googleusercontent.com/5rcQ32ml8E5ONp9f9-Rf78IofLb9QjS5_0mqsY1zEFc=w400-h300-no-n" width="400" height="300"></amp-img><amp-img src="https://lh3.googleusercontent.com/Z4gtm5Bkxyv21Z2PtbTf95Clb9AE4VTR6olbBKYrenM=w400-h300-no-n" width="400" height="300"></amp-img><amp-soundcloud height="300" layout="fixed-height" data-trackid="243169232"></amp-soundcloud><amp-youtube data-videoid="mGENRKrdoGY" width="400" height="300"></amp-youtube><amp-anim src="https://lh3.googleusercontent.com/qNn8GDz8Jfd-s9lt3Nc4lJeLjVyEaqGJTk1vuCUWazCmAeOBVjSWDD0SMTU7x0zhVe5UzOTKR0n-kN4SXx7yElvpKYvCMaRyS_g-jydhJ_cEVYmYPiZ_j1Y9de43mlKxU6s06uK1NAlpbSkL_046amEKOdgIACICkuWfOBwlw2hUDfjPOWskeyMrcTu8XOEerCLuVqXugG31QC345hz3lUyOlkdT9fMYVUynSERGNzHba7bXMOxKRe3izS5DIWUgJs3oeKYqA-V8iqgCvneD1jj0Ff68V_ajm4BDchQubBJU0ytXVkoWh27ngeEHubpnApOS6fcGsjPxeuMjnzAUtoTsiXz2FZi1mMrxrblJ-kZoAq1DJ95cnoqoa2CYq3BTgq2E8BRe2paNxLJ5GXBCTpNdXMpVJc6eD7ceijQyn-2qanilX-iK3ChbOq0uBHMvsdoC_LsFOu5KzbbNH71vM3DPkvDGmHJmF67Vj8sQ6uBrLnzpYlCyN4-Y9frR8zugDcqX5Q=w400-h300-no" width="400" height="300"><amp-img placeholder="" src="https://lh3.googleusercontent.com/qNn8GDz8Jfd-s9lt3Nc4lJeLjVyEaqGJTk1vuCUWazCmAeOBVjSWDD0SMTU7x0zhVe5UzOTKR0n-kN4SXx7yElvpKYvCMaRyS_g-jydhJ_cEVYmYPiZ_j1Y9de43mlKxU6s06uK1NAlpbSkL_046amEKOdgIACICkuWfOBwlw2hUDfjPOWskeyMrcTu8XOEerCLuVqXugG31QC345hz3lUyOlkdT9fMYVUynSERGNzHba7bXMOxKRe3izS5DIWUgJs3oeKYqA-V8iqgCvneD1jj0Ff68V_ajm4BDchQubBJU0ytXVkoWh27ngeEHubpnApOS6fcGsjPxeuMjnzAUtoTsiXz2FZi1mMrxrblJ-kZoAq1DJ95cnoqoa2CYq3BTgq2E8BRe2paNxLJ5GXBCTpNdXMpVJc6eD7ceijQyn-2qanilX-iK3ChbOq0uBHMvsdoC_LsFOu5KzbbNH71vM3DPkvDGmHJmF67Vj8sQ6uBrLnzpYlCyN4-Y9frR8zugDcqX5Q=w400-h300-no-k" width="400" height="300"></amp-img></amp-anim><amp-audio src="https://ia801402.us.archive.org/16/items/EDIS-SRP-0197-06/EDIS-SRP-0197-06.mp3"></amp-audio><amp-brightcove data-account="906043040001" data-video-id="1401169490001" data-player="180a5658-8be8-4f33-8eba-d562ab41b40c" layout="responsive" width="300" height="300"></amp-brightcove><amp-vimeo data-videoid="27246366" width="300" height="300"></amp-vimeo><amp-dailymotion data-videoid="x3rdtfy" width="300" height="300"></amp-dailymotion><amp-vine data-vineid="MdKjXez002d" width="300" height="300"></amp-vine><amp-video src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4" width="300" height="300" controls=""></amp-video></amp-carousel>',
			),

			'amp-dailymotion'                                           => array(
				'<amp-dailymotion data-videoid="x3rdtfy" width="500" height="281"></amp-dailymotion><h4>Default (responsive)</h4><amp-dailymotion data-videoid="x3rdtfy" width="500" height="281" layout="responsive"></amp-dailymotion><h4>Custom</h4><amp-dailymotion data-videoid="x3rdtfy" data-endscreen-enable="false" data-sharing-enable="false" data-ui-highlight="444444" data-ui-logo="false" data-info="false" width="640" height="360"></amp-dailymotion>',
				'<amp-dailymotion data-videoid="x3rdtfy" width="500" height="281"></amp-dailymotion><h4>Default (responsive)</h4><amp-dailymotion data-videoid="x3rdtfy" width="500" height="281" layout="responsive"></amp-dailymotion><h4>Custom</h4><amp-dailymotion data-videoid="x3rdtfy" data-endscreen-enable="false" data-sharing-enable="false" data-ui-highlight="444444" data-ui-logo="false" data-info="false" width="640" height="360"></amp-dailymotion>',
			),

			'doubleclick-1'                                             => array(
				'<amp-ad width="480" height="75" type="doubleclick" data-slot="/4119129/mobile_ad_banner" data-multi-size="320x50" class="dashedborder"></amp-ad>',
				'<amp-ad width="480" height="75" type="doubleclick" data-slot="/4119129/mobile_ad_banner" data-multi-size="320x50" class="dashedborder"></amp-ad>',
			),

			'facebook'                                                  => array(
				'<amp-facebook width="552" height="303" layout="responsive" data-href="https://www.facebook.com/zuck/posts/10102593740125791"></amp-facebook><h1>More Posts</h1>',
				'<amp-facebook width="552" height="303" layout="responsive" data-href="https://www.facebook.com/zuck/posts/10102593740125791"></amp-facebook><h1>More Posts</h1>',
			),

			'font'                                                      => array(
				'<amp-font layout="nodisplay" font-family="Comic AMP" timeout="2000"></amp-font><amp-font layout="nodisplay" font-family="Comic AMP Bold" timeout="3000" font-weight="bold"></amp-font>',
				'<amp-font layout="nodisplay" font-family="Comic AMP" timeout="2000"></amp-font><amp-font layout="nodisplay" font-family="Comic AMP Bold" timeout="3000" font-weight="bold"></amp-font>',
			),

			'form'                                                      => array(
				'<form method="get" action="/form/search-html/get" target="_blank"><fieldset><label><span>Search for</span><input type="search" name="term" required=""/></label><input type="submit" value="Search"/></fieldset></form>',
				'<form method="get" action="/form/search-html/get" target="_blank"><fieldset><label><span>Search for</span><input type="search" name="term" required=""/></label><input type="submit" value="Search"/></fieldset></form>',
			),

			'gfycat'                                                    => array(
				'<amp-gfycat data-gfyid="BareSecondaryFlamingo" width="225" height="400"></amp-gfycat>',
				'<amp-gfycat data-gfyid="BareSecondaryFlamingo" width="225" height="400"></amp-gfycat>',
			),

			'h2'                                                        => array(
				'<h2>Example Text</h2>',
			),

			'empty_element'                                             => array(
				'<br/>',
			),

			'merge_two_attr_specs'                                      => array(
				'<div submit-success>Whatever</div>',
				'<div>Whatever</div>',
			),

			'attribute_value_blacklisted_by_regex_removed'              => array(
				'<a href="__amp_source_origin">Click me.</a>',
				'<a href="">Click me.</a>',
			),

			'host_relative_url_allowed'                                 => array(
				'<a href="/path/to/content">Click me.</a>',
			),

			'protocol_relative_url_allowed'                             => array(
				'<a href="//example.com/path/to/content">Click me.</a>',
			),

			'node_with_whiteilsted_protocol_http_allowed'               => array(
				'<a href="http://example.com/path/to/content">Click me.</a>',
			),

			'node_with_whiteilsted_protocol_https_allowed'              => array(
				'<a href="https://example.com/path/to/content">Click me.</a>',
			),

			'node_with_whiteilsted_protocol_fb-messenger_allowed'       => array(
				'<a href="fb-messenger://example.com/path/to/content">Click me.</a>',
			),

			'attribute_value_valid'                                     => array(
				'<template type="amp-mustache">Template Data</template>',
			),

			'attribute_value_invalid'                                   => array(
				// type is mandatory, so the node is removed.
				'<template type="bad-type">Template Data</template>',
				'',
			),

			'attribute_amp_accordion_value'                             => array(
				'<amp-accordion disable-session-states="">test</amp-accordion>',
			),

			'attribute_value_with_blacklisted_regex_removed'            => array(
				'<a rel="import">Click me.</a>',
				'<a>Click me.</a>',
			),

			'attribute_value_with_blacklisted_multi-part_regex_removed' => array(
				'<a rel="something else import">Click me.</a>',
				'<a>Click me.</a>',
			),

			'attribute_value_with_required_regex'                       => array(
				'<a target="_blank">Click me.</a>',
			),

			'attribute_value_with_disallowed_required_regex_removed'    => array(
				'<a target="_not_blank">Click me.</a>',
				'<a>Click me.</a>',
			),

			'attribute_value_with_required_value_casei_lower'           => array(
				'<a type="text/html">Click.me.</a>',
			),

			'attribute_value_with_required_value_casei_upper'           => array(
				'<a type="TEXT/HTML">Click.me.</a>',
			),

			'attribute_value_with_required_value_casei_mixed'           => array(
				'<a type="TeXt/HtMl">Click.me.</a>',
			),

			'attribute_value_with_bad_value_casei_removed'              => array(
				'<a type="bad_type">Click.me.</a>',
				'<a>Click.me.</a>',
			),

			'attribute_value_with_value_regex_casei_lower'              => array(
				'<amp-dailymotion data-videoid="abc"></amp-dailymotion>',
			),

			'attribute_value_with_value_regex_casei_upper'              => array(
				'<amp-dailymotion data-videoid="ABC"></amp-dailymotion>',
			),

			'attribute_value_with_bad_value_regex_casei_removed'        => array(
				// data-ui-logo should be true|false.
				'<amp-dailymotion data-videoid="123" data-ui-logo="maybe"></amp-dailymotion>',
				'<amp-dailymotion data-videoid="123"></amp-dailymotion>',
			),

			'attribute_bad_attr_with_no_value_removed'                  => array(
				'<amp-ad type="adsense" bad-attr-no-value>something here</amp-alt>',
				'<amp-ad type="adsense">something here</amp-ad>',
			),

			'attribute_bad_attr_with_value_removed'                     => array(
				'<amp-ad type="adsense" bad-attr="some-value">something here</amp-alt>',
				'<amp-ad type="adsense">something here</amp-ad>',
			),

			'remove_node_with_invalid_mandatory_attribute'              => array(
				// script only allows application/json, nothing else.
				'<script type="type/javascript">console.log()</script>',
				'',
			),

			'remove_node_without_mandatory_attribute'                   => array(
				'<script>console.log()</script>',
				'',
			),

			'remove_script_with_async_attribute'                        => array(
				'<script async src="//cdn.someecards.com/assets/embed/embed-v1.07.min.js" charset="utf-8"></script>', // phpcs:ignore
				'',
			),

			'allow_node_with_valid_mandatory_attribute'                 => array(
				'<amp-analytics><script type="application/json"></script></amp-analytics>',
			),

			'nodes_with_non_whitelisted_tags_replaced_by_children'      => array(
				'<invalid_tag>this is some text inside the invalid node</invalid_tag>',
				'this is some text inside the invalid node',
			),

			'empty_parent_nodes_of_non_whitelisted_tags_removed'        => array(
				'<div><span><span><invalid_tag></invalid_tag></span></span></div>',
				'',
			),

			'replace_non_whitelisted_node_with_children'                => array(
				'<p>This is some text <invalid_tag>with a disallowed tag</invalid_tag> in the middle of it.</p>',
				'<p>This is some text with a disallowed tag in the middle of it.</p>',
			),

			'remove_attribute_on_node_with_missing_mandatory_parent'    => array(
				'<div submit-success>This is a test.</div>',
				'<div>This is a test.</div>',
			),

			'leave_attribute_on_node_with_present_mandatory_parent'     => array(
				'<form action="form.php" target="_top"><div submit-success>This is a test.</div></form>',
				'<form action="form.php" target="_top"><div submit-success="">This is a test.</div></form>',
			),

			'disallowed_empty_attr_removed'                             => array(
				'<amp-user-notification data-dismiss-href></amp-user-notification>',
				'<amp-user-notification></amp-user-notification>',
			),

			'allowed_empty_attr'                                        => array(
				'<a border=""></a>',
			),

			'remove_node_with_disallowed_ancestor'                      => array(
				'<amp-sidebar>The sidebar<amp-app-banner>This node is not allowed here.</amp-app-banner></amp-sidebar>',
				'<amp-sidebar>The sidebar</amp-sidebar>',
			),

			'remove_node_without_mandatory_ancestor'                    => array(
				'<div>All I have is this div, when all you want is a noscript tag.<audio>Sweet tunes</audio></div>',
				'<div>All I have is this div, when all you want is a noscript tag.</div>',
			),

			'amp-img_with_good_protocols'                               => array(
				'<amp-img src="https://example.com/resource1" srcset="https://example.com/resource1, https://example.com/resource2"></amp-img>',
			),

			'allowed_tag_only'                                          => array(
				'<p>Text</p><img src="/path/to/file.jpg" />',
				'<p>Text</p>',
			),

			'disallowed_attributes'                                     => array(
				'<a href="/path/to/file.jpg" style="border: 1px solid red;">Link</a>',
				'<a href="/path/to/file.jpg">Link</a>',
			),

			'onclick_attribute'                                         => array(
				'<a href="/path/to/file.jpg" onclick="alert(e);">Link</a>',
				'<a href="/path/to/file.jpg">Link</a>',
			),

			'on_attribute'                                              => array(
				'<button on="tap:my-lightbox">Tap Me</button>',
			),

			'multiple_disallowed_attributes'                            => array(
				'<a href="/path/to/file.jpg" style="border: 1px solid red;" onclick="alert(e);">Link</a>',
				'<a href="/path/to/file.jpg">Link</a>',
			),

			'attribute_recursive'                                       => array(
				'<div style="border: 1px solid red;"><a href="/path/to/file.jpg" onclick="alert(e);">Hello World</a></div>',
				'<div><a href="/path/to/file.jpg">Hello World</a></div>',
			),

			'no_strip_amp_tags'                                         => array(
				'<amp-img src="http://example.com/path/to/file.jpg" width="300" height="300"></amp-img>',
			),

			'a_with_attachment_rel'                                     => array(
				'<a href="http://example.com" rel="wp-att-1686">Link</a>',
			),

			'a_with_attachment_rel_plus_another_valid_value'            => array(
				'<a href="http://example.com" rel="attachment wp-att-1686">Link</a>',
			),

			'a_with_rev'                                                => array(
				'<a href="http://example.com" rev="footnote">Link</a>',
			),

			'a_with_target_blank'                                       => array(
				'<a href="http://example.com" target="_blank">Link</a>',
			),

			'a_with_target_uppercase_blank'                             => array(
				'<a href="http://example.com" target="_BLANK">Link</a>',
				'<a href="http://example.com">Link</a>',
			),

			'a_with_target_new'                                         => array(
				'<a href="http://example.com" target="_new">Link</a>',
				'<a href="http://example.com">Link</a>',
			),

			'a_with_target_self'                                        => array(
				'<a href="http://example.com" target="_self">Link</a>',
			),

			'a_with_target_invalid'                                     => array(
				'<a href="http://example.com" target="boom">Link</a>',
				'<a href="http://example.com">Link</a>',
			),

			'a_with_href_invalid'                                       => array(
				'<a href="some random text">Link</a>',
			),

			'a_with_href_scheme_tel'                                    => array(
				'<a href="tel:4166669999">Call Me, Maybe</a>',
			),

			'a_with_href_scheme_sms'                                    => array(
				'<a href="sms:4166669999">SMS Me, Maybe</a>',
			),

			'a_with_href_scheme_mailto'                                 => array(
				'<a href="mailto:email@example.com">Email Me, Maybe</a>',
			),

			'a_with_href_relative'                                      => array(
				'<a href="/home">Home</a>',
			),

			'a_with_anchor'                                             => array(
				'<a href="#section2">Home</a>',
			),

			'a_is_anchor'                                               => array(
				'<a name="section2"></a>',
			),

			'a_is_achor_with_id'                                        => array(
				'<a id="section3"></a>',
			),

			'a_empty'                                                   => array(
				'<a>Hello World</a>',
			),

			'a_empty_with_children_with_restricted_attributes'          => array(
				'<a><span style="color: red;">Red</span>&amp;<span style="color: blue;">Orange</span></a>',
				'<a><span>Red</span>&amp;<span>Orange</span></a>',
			),

			'h1_with_size'                                              => array(
				'<h1 size="1">Headline</h1>',
				'<h1>Headline</h1>',
			),

			'font'                                                      => array(
				'<font size="1">Headline</font>',
				'Headline',
			),

			// font is removed so we should check that other elements are checked as well.
			'font_with_other_bad_elements'                              => array(
				'<font size="1">Headline</font><span style="color: blue">Span</span>',
				'Headline<span>Span</span>',
			),
		);
	}

	public function setUp() {
		$this->allowed_tags = AMP_Allowed_Tags_Generated::get_allowed_tags();
		$this->globally_allowed_attributes = AMP_Allowed_Tags_Generated::get_allowed_attributes();
		$this->layout_allowed_attributes = AMP_Allowed_Tags_Generated::get_allowed_attributes();
	}

	/**
	 * Tests is_missing_mandatory_attribute
	 *
	 * @see AMP_Tag_And_Attribute_Sanitizer::is_missing_mandatory_attribute()
	 */
	public function test_is_missing_mandatory_attribute() {
		$spec = array(
			'data-gistid' => array(
				'mandatory' => true,
			),
			'noloading'   => array(),
		);
		$dom  = new DomDocument();
		$node = new DOMElement( 'amp-gist' );
		$dom->appendChild( $node );
		$sanitizer = new AMP_Tag_And_Attribute_Sanitizer( $dom );
		$this->assertTrue( $sanitizer->is_missing_mandatory_attribute( $spec, $node ) );

		$node->setAttribute( 'data-gistid', 'foo-value' );
		$this->assertFalse( $sanitizer->is_missing_mandatory_attribute( $spec, $node ) );

		$spec_non_array = new stdClass();
		$this->assertFalse( $sanitizer->is_missing_mandatory_attribute( $spec_non_array, $node ) );
	}

	/**
	 * Test sanitization of tags and attributes.
	 *
	 * @dataProvider get_data
	 * @group allowed-tags
	 * @param string $source Markup to process.
	 * @param string $expected The markup to expect.
	 */
	public function test_sanitizer( $source, $expected = null ) {
		$expected  = isset( $expected ) ? $expected : $source;
		$dom       = AMP_DOM_Utils::get_dom_from_content( $source );
		$sanitizer = new AMP_Tag_And_Attribute_Sanitizer( $dom );
		$sanitizer->sanitize();
		$content = AMP_DOM_Utils::get_content_from_dom( $dom );
		$this->assertEquals( $expected, $content );
	}
}
