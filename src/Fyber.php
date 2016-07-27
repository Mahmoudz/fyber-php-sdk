<?php

namespace mahmoudz\fyberPhpSdk;

use mahmoudz\fyberPhpSdk\Contracts\FyberInterface;
use mahmoudz\fyberPhpSdk\Exceptions\MissingApiKeyException;
use mahmoudz\fyberPhpSdk\Exceptions\MissingRequiredDataException;

/**
 * Class Fyber
 *
 * @author  Mahmoud Zalt  <mahmoud@zalt.me>
 */
class Fyber implements FyberInterface
{

    public $api_key;

    protected $base_url;

    protected $api_version;

    protected $response_format;

    private $httpClient;

    private $config;

    /**
     * Fyber constructor.
     *
     * @param \mahmoudz\fyberPhpSdk\HttpClient $httpClient
     * @param \mahmoudz\fyberPhpSdk\Config     $config
     */
    public function __construct(HttpClient $httpClient, Config $config)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;

        $this->readConfig();
    }


    /**
     * refer the docs for what needs to be passed in:
     *      http://developer.fyber.com/content/current/android/offer-wall/offer-api/
     *      http://developer.fyber.com/content/current/ios/offer-wall/offer-api/
     *
     * @param array $data
     * @param       $appType
     *
     * @return  mixed
     * @throws \mahmoudz\fyberPhpSdk\WrongAppTypeException
     */
    public function getOffers(Array $data, $appType)
    {
        $endpoint = "offers";

        // append the app id based on the app type
        $data['appid'] = $this->getAppId($appType);

        // validate the data is correct and complete
        $data = $this->validateData($data);

        // append the hash key to the data
        $data['hashkey'] = $this->calculateHashKey($data);

        // build the full query parameter from the data
        $parametersQuery = http_build_query($data);

        $uri = $this->base_url . "v" . $this->api_version . "/" . $endpoint . '.' . $this->response_format . "?" . $parametersQuery;

        $headers = ['Accept' => 'application/json'];

        $result = $this->httpClient->get($uri, $headers);
        $content = $result->getBody()->getContents();
//        $content = '{"code":"OK","message":"Ok","count":30,"pages":2,"information":{"app_name":"RewardsFox (Android)","appid":22222,"virtual_currency":"VC 2","virtual_currency_sale_enabled":false,"country":"LB","language":"EN","support_url":"http://offer.fyber.com/mobile/support?appid=46453&client=api&uid=1"},"offers":[{"title":"Galaxy Races","offer_id":993565,"teaser":"Login, get 200 points to get your reward & stay alive until the stopwatch","required_actions":"Login, get 200 points to get your reward & stay alive until the stopwatch","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=993565&os_version=4.1&ad_format=offer&group=Fyber&sig=ae6a0492e69135824df51da97a667b326e38ee31","offer_types":[{"offer_type_id":105,"readable":"Registration"},{"offer_type_id":112,"readable":"Free"}],"payout":6,"time_to_payout":{"amount":600,"readable":"10 minutes"},"thumbnail":{"lowres":"http://cdn1.sponsorpay.com/assets/64109/galaxy_races_square_60.png","hires":"http://cdn1.sponsorpay.com/assets/64109/galaxy_races_square_175.png"}},{"title":"3Lines Car","offer_id":994531,"teaser":"Login, get 200 points to receive your reward.","required_actions":"Login, get 200 points to receive your reward.","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=994531&os_version=4.1&ad_format=offer&group=Fyber&sig=f977fa03ec11a1fc1c5e9a7c60ec835fdec5770d","offer_types":[{"offer_type_id":105,"readable":"Registration"}],"payout":8,"time_to_payout":{"amount":900,"readable":"15 minutes"},"thumbnail":{"lowres":"http://cdn3.sponsorpay.com/assets/64143/3line_car_square_60.png","hires":"http://cdn3.sponsorpay.com/assets/64143/3line_car_square_175.png"}},{"title":"Play Mouse Runner! ","offer_id":987996,"teaser":"Complete the level 1 by reaching 200 points.","required_actions":"Complete the level 1 by reaching 200 points.","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=987996&os_version=4.1&ad_format=offer&group=Fyber&sig=a95678eb01e240e4c793e682184575ff32c1b956","offer_types":[{"offer_type_id":105,"readable":"Registration"}],"payout":12,"time_to_payout":{"amount":600,"readable":"10 minutes"},"thumbnail":{"lowres":"http://cdn2.sponsorpay.com/assets/63995/Mouse_Runner_square_60.png","hires":"http://cdn2.sponsorpay.com/assets/63995/Mouse_Runner_square_175.png"}},{"title":"Frog Jumper","offer_id":995751,"teaser":"Login, get 100 points to receive your reward.","required_actions":"Login, get 100 points to receive your reward.","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=995751&os_version=4.1&ad_format=offer&group=Fyber&sig=8dd530fa91675c9d0015a8c445e51f6355f389cd","offer_types":[{"offer_type_id":105,"readable":"Registration"}],"payout":8,"time_to_payout":{"amount":600,"readable":"10 minutes"},"thumbnail":{"lowres":"http://cdn1.sponsorpay.com/assets/64179/frog_jumper_square_60.png","hires":"http://cdn1.sponsorpay.com/assets/64179/frog_jumper_square_175.png"}},{"title":"Candy Switch","offer_id":1001974,"teaser":"Login, get 300 points to receive your reward.","required_actions":"Login, get 300 points to receive your reward.","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=1001974&os_version=4.1&ad_format=offer&group=Fyber&sig=a8fda95691e0a455ddbaedcac2c78721c0897e61","offer_types":[{"offer_type_id":105,"readable":"Registration"}],"payout":6,"time_to_payout":{"amount":600,"readable":"10 minutes"},"thumbnail":{"lowres":"http://cdn2.sponsorpay.com/assets/64325/candy_switch_square_60.png","hires":"http://cdn2.sponsorpay.com/assets/64325/candy_switch_square_175.png"}},{"title":"Diamonds Digger","offer_id":980549,"teaser":"Login, get 200 points to get your reward & stay alive until the stopwatch","required_actions":"Login, get 200 points to get your reward & stay alive until the stopwatch","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=980549&os_version=4.1&ad_format=offer&group=Fyber&sig=cccaf5bedcad5f5fef48bb35b4b9b735e831ad7e","offer_types":[{"offer_type_id":105,"readable":"Registration"}],"payout":12,"time_to_payout":{"amount":1500,"readable":"25 minutes"},"thumbnail":{"lowres":"http://cdn3.sponsorpay.com/assets/63824/Diamonds_Digger_square_60.png","hires":"http://cdn3.sponsorpay.com/assets/63824/Diamonds_Digger_square_175.png"}},{"title":"Play Dual Hero","offer_id":986543,"teaser":"Login, get 200 points to receive your reward.","required_actions":"Login, get 200 points to receive your reward.","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=986543&os_version=4.1&ad_format=offer&group=Fyber&sig=345940cc4554b23055311ee833dd02fd36c8e459","offer_types":[{"offer_type_id":105,"readable":"Registration"}],"payout":19,"time_to_payout":{"amount":300,"readable":"5 minutes"},"thumbnail":{"lowres":"http://cdn1.sponsorpay.com/assets/51957/Dual-Hero_square_60.jpg","hires":"http://cdn1.sponsorpay.com/assets/51957/Dual-Hero_square_175.jpg"}},{"title":"Kitty Sprint","offer_id":1010372,"teaser":"Login, get 200 points to receive your reward.","required_actions":"Login, get 200 points to receive your reward.","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=1010372&os_version=4.1&ad_format=offer&group=Fyber&sig=68846190723961d350fda1d5bbcb496a6bc9a447","offer_types":[{"offer_type_id":105,"readable":"Registration"}],"payout":24,"time_to_payout":{"amount":600,"readable":"10 minutes"},"thumbnail":{"lowres":"http://cdn4.sponsorpay.com/assets/64465/kitty_sprint_square_60.png","hires":"http://cdn4.sponsorpay.com/assets/64465/kitty_sprint_square_175.png"}},{"title":"Maths and Chalk Challenge","offer_id":990828,"teaser":"Solve the calculations and reach 88 points","required_actions":"Solve the calculations and reach 88 points","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=990828&os_version=4.1&ad_format=offer&group=Fyber&sig=364fa85e98aeb05454a72e5a5d7e3832c54cb62d","offer_types":[{"offer_type_id":105,"readable":"Registration"}],"payout":12,"time_to_payout":{"amount":600,"readable":"10 minutes"},"thumbnail":{"lowres":"http://cdn3.sponsorpay.com/assets/52443/unnamed_square_60.jpg","hires":"http://cdn3.sponsorpay.com/assets/52443/unnamed_square_175.jpg"}},{"title":"Play Less or More! ","offer_id":987105,"teaser":"Login, complete level 1 and get 200 points to get your reward","required_actions":"Login, complete level 1 and get 200 points to get your reward","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=987105&os_version=4.1&ad_format=offer&group=Fyber&sig=66b59f8153fff8876bd3c36cea28c687c7814624","offer_types":[{"offer_type_id":105,"readable":"Registration"}],"payout":19,"time_to_payout":{"amount":2400,"readable":"40 minutes"},"thumbnail":{"lowres":"http://cdn2.sponsorpay.com/assets/56333/Less-or-more_square_60.PNG","hires":"http://cdn2.sponsorpay.com/assets/56333/Less-or-more_square_175.PNG"}},{"title":"Shape Matcher","offer_id":985609,"teaser":"Login, complete level 1 and get 300 points to get your reward","required_actions":"Login, complete level 1 and get 300 points to get your reward","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=985609&os_version=4.1&ad_format=offer&group=Fyber&sig=1f6ae9549b24ac89587dffa8fb588e11a0cc7342","offer_types":[{"offer_type_id":105,"readable":"Registration"}],"payout":6,"time_to_payout":{"amount":900,"readable":"15 minutes"},"thumbnail":{"lowres":"http://cdn4.sponsorpay.com/assets/63948/shape_matcher_square_60.png","hires":"http://cdn4.sponsorpay.com/assets/63948/shape_matcher_square_175.png"}},{"title":"Monkey Jump","offer_id":985736,"teaser":"Login, get 200 points and stay alive to receive your reward.","required_actions":"Login, get 200 points and stay alive to receive your reward.","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=985736&os_version=4.1&ad_format=offer&group=Fyber&sig=b82b8ee587e9ba79b508e71de1b961c781cb8a5b","offer_types":[{"offer_type_id":105,"readable":"Registration"}],"payout":6,"time_to_payout":{"amount":600,"readable":"10 minutes"},"thumbnail":{"lowres":"http://cdn1.sponsorpay.com/assets/63949/monkey_jump_square_60.png","hires":"http://cdn1.sponsorpay.com/assets/63949/monkey_jump_square_175.png"}},{"title":"Play Candy Maths! ","offer_id":987107,"teaser":"Win the candy calculation party by reaching 150 points","required_actions":"Win the candy calculation party by reaching 150 points","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=987107&os_version=4.1&ad_format=offer&group=Fyber&sig=b7003c91bbfbbe4721a8560920e9ac149b608571","offer_types":[{"offer_type_id":105,"readable":"Registration"}],"payout":19,"time_to_payout":{"amount":600,"readable":"10 minutes"},"thumbnail":{"lowres":"http://cdn2.sponsorpay.com/assets/52845/Candy_Math_square_60.png","hires":"http://cdn2.sponsorpay.com/assets/52845/Candy_Math_square_175.png"}},{"title":"AliExpress Shopping App","offer_id":1020649,"teaser":"Download and START","required_actions":"Download and START","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=1020649&os_version=4.1&ad_format=offer&group=Fyber&sig=3270c7d277d9e53ffdb680b48b5ac20f9b8e3a9a","offer_types":[{"offer_type_id":101,"readable":"Download"},{"offer_type_id":112,"readable":"Free"}],"payout":14,"time_to_payout":{"amount":1800,"readable":"30 minutes"},"thumbnail":{"lowres":"http://cdn2.sponsorpay.com/app_icons/35601/small_mobile_icon.png","hires":"http://cdn2.sponsorpay.com/app_icons/35601/big_mobile_icon.png"}},{"title":"Step Tiles","offer_id":1010339,"teaser":"Login, get 200 points to receive your reward.","required_actions":"Login, get 200 points to receive your reward.","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=1010339&os_version=4.1&ad_format=offer&group=Fyber&sig=089ce273ceab3ae664146303a0eed7229ce098c8","offer_types":[{"offer_type_id":105,"readable":"Registration"}],"payout":6,"time_to_payout":{"amount":600,"readable":"10 minutes"},"thumbnail":{"lowres":"http://cdn3.sponsorpay.com/assets/64464/step_tiles_square_60.png","hires":"http://cdn3.sponsorpay.com/assets/64464/step_tiles_square_175.png"}},{"title":"Moon Catcher","offer_id":1001975,"teaser":"Login, get 200 points to receive your reward.","required_actions":"Login, get 200 points to receive your reward.","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=1001975&os_version=4.1&ad_format=offer&group=Fyber&sig=dee9027c4ce730c77930966a82af39685c11ac36","offer_types":[{"offer_type_id":105,"readable":"Registration"}],"payout":12,"time_to_payout":{"amount":900,"readable":"15 minutes"},"thumbnail":{"lowres":"http://cdn3.sponsorpay.com/assets/64326/moon_catcher_square_60.png","hires":"http://cdn3.sponsorpay.com/assets/64326/moon_catcher_square_175.png"}},{"title":"Candy Catch","offer_id":992979,"teaser":"Login, reach 231 points AND stay alive until the end of the chrono ","required_actions":"Login, reach 231 points AND stay alive until the end of the chrono ","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=992979&os_version=4.1&ad_format=offer&group=Fyber&sig=967882890fdfe0f24906605d8c4815b5b566fda5","offer_types":[{"offer_type_id":105,"readable":"Registration"}],"payout":12,"time_to_payout":{"amount":600,"readable":"10 minutes"},"thumbnail":{"lowres":"http://cdn4.sponsorpay.com/assets/59963/Screen_Shot_2015-11-26_at_13.45.08_original.png","hires":"http://cdn4.sponsorpay.com/assets/59963/Screen_Shot_2015-11-26_at_13.45.08_original.png"}},{"title":"Hungry Bees","offer_id":985529,"teaser":"Login, get 200 points to get your reward & stay alive until the stopwatch","required_actions":"Login, get 200 points to get your reward & stay alive until the stopwatch","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=985529&os_version=4.1&ad_format=offer&group=Fyber&sig=21584a44b9b3127a2465a46ac9c7ef2e3c0f9968","offer_types":[{"offer_type_id":105,"readable":"Registration"}],"payout":6,"time_to_payout":{"amount":600,"readable":"10 minutes"},"thumbnail":{"lowres":"http://cdn1.sponsorpay.com/assets/63944/hungry_bee_square_60.png","hires":"http://cdn1.sponsorpay.com/assets/63944/hungry_bee_square_175.png"}},{"title":"Play Ring Line for Free! ","offer_id":564501,"teaser":"1. Login.\r\n2. Get the maximum of stars and get 106 points. Get rewarded.","required_actions":"1. Login.\r\n2. Get the maximum of stars and get 106 points. Get rewarded.","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=564501&os_version=4.1&ad_format=offer&group=Fyber&sig=3ce29f32b4edb007d910845f2b21f2405b391d9b","offer_types":[{"offer_type_id":105,"readable":"Registration"},{"offer_type_id":112,"readable":"Free"}],"payout":6,"time_to_payout":{"amount":600,"readable":"10 minutes"},"thumbnail":{"lowres":"http://cdn1.sponsorpay.com/assets/53169/ring_square_60.png","hires":"http://cdn1.sponsorpay.com/assets/53169/ring_square_175.png"}},{"title":"Identical Brain","offer_id":889485,"teaser":"Login, get 40 points to receive your reward.","required_actions":"Login, get 40 points to receive your reward.","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=889485&os_version=4.1&ad_format=offer&group=Fyber&sig=06a91bc6bc305298774f4ef227fd229a80f86c39","offer_types":[{"offer_type_id":105,"readable":"Registration"}],"payout":16,"time_to_payout":{"amount":1200,"readable":"20 minutes"},"thumbnail":{"lowres":"http://cdn3.sponsorpay.com/assets/58364/brain_square_60.png","hires":"http://cdn3.sponsorpay.com/assets/58364/brain_square_175.png"}},{"title":"Washing Colors","offer_id":539718,"teaser":"Complete the level 1 by reaching 33 points.","required_actions":"Complete the level 1 by reaching 33 points.","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=539718&os_version=4.1&ad_format=offer&group=Fyber&sig=cad7db2b47815363c17441cae7377429fd973aaa","offer_types":[{"offer_type_id":105,"readable":"Registration"},{"offer_type_id":106,"readable":"Games"},{"offer_type_id":109,"readable":"Games"},{"offer_type_id":112,"readable":"Free"}],"payout":22,"time_to_payout":{"amount":600,"readable":"10 minutes"},"thumbnail":{"lowres":"http://cdn3.sponsorpay.com/assets/54348/Screen_Shot_2015-07-22_at_4.34.55_PM_square_60.png","hires":"http://cdn3.sponsorpay.com/assets/54348/Screen_Shot_2015-07-22_at_4.34.55_PM_square_175.png"}},{"title":"Rocket Moon","offer_id":945153,"teaser":"Login, get 300 points to receive your reward.","required_actions":"Login, get 300 points to receive your reward.","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=945153&os_version=4.1&ad_format=offer&group=Fyber&sig=2d0b11908956ecbae5d3fe45c0c1de458014df43","offer_types":[{"offer_type_id":105,"readable":"Registration"}],"payout":9,"time_to_payout":{"amount":900,"readable":"15 minutes"},"thumbnail":{"lowres":"http://cdn1.sponsorpay.com/assets/63393/Screen_Shot_2016-04-01_at_12.44.09_original.png","hires":"http://cdn1.sponsorpay.com/assets/63393/Screen_Shot_2016-04-01_at_12.44.09_original.png"}},{"title":"* Our Daily App *","offer_id":923783,"teaser":"Download and START","required_actions":"Download and START","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=923783&os_version=4.1&ad_format=offer&group=Fyber&sig=de34416f089b1fc2a84671401f030f365406ba86","offer_types":[{"offer_type_id":101,"readable":"Download"},{"offer_type_id":106,"readable":"Games"},{"offer_type_id":109,"readable":"Games"},{"offer_type_id":112,"readable":"Free"}],"payout":18,"time_to_payout":{"amount":1800,"readable":"30 minutes"},"thumbnail":{"lowres":"http://cdn4.sponsorpay.com/assets/45087/android_square_60.png","hires":"http://cdn4.sponsorpay.com/assets/45087/android_square_175.png"}},{"title":"Penta Bomb WWW","offer_id":859755,"teaser":"Login, get 200 points to receive your reward.","required_actions":"Login, get 200 points to receive your reward.","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=859755&os_version=4.1&ad_format=offer&group=Fyber&sig=9444a986c95e66c82a40296f4a4740ebab97ba8a","offer_types":[{"offer_type_id":105,"readable":"Registration"}],"payout":12,"time_to_payout":{"amount":600,"readable":"10 minutes"},"thumbnail":{"lowres":"http://cdn4.sponsorpay.com/assets/62261/Screen_Shot_2016-02-22_at_00.44.13_square_60.png","hires":"http://cdn4.sponsorpay.com/assets/62261/Screen_Shot_2016-02-22_at_00.44.13_square_175.png"}},{"title":"Crazy Rain","offer_id":640886,"teaser":"Login and get 100 points & staying alive to receive your reward.","required_actions":"Login and get 100 points & staying alive to receive your reward.","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=640886&os_version=4.1&ad_format=offer&group=Fyber&sig=42132d35de2d70e5f1787f201b026052ab3de6ef","offer_types":[{"offer_type_id":105,"readable":"Registration"},{"offer_type_id":112,"readable":"Free"}],"payout":25,"time_to_payout":{"amount":600,"readable":"10 minutes"},"thumbnail":{"lowres":"http://cdn3.sponsorpay.com/assets/56756/Crazy_rain_icon_square_60.png","hires":"http://cdn3.sponsorpay.com/assets/56756/Crazy_rain_icon_square_175.png"}},{"title":"Fast-Circle WWW","offer_id":842157,"teaser":"Login, get 380 points to receive your reward.","required_actions":"Login, get 380 points to receive your reward.","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=842157&os_version=4.1&ad_format=offer&group=Fyber&sig=492fb33dcd0a18a1e46026d1cc13c5a60431a89c","offer_types":[{"offer_type_id":105,"readable":"Registration"}],"payout":12,"time_to_payout":{"amount":900,"readable":"15 minutes"},"thumbnail":{"lowres":"http://cdn2.sponsorpay.com/assets/62101/Screen_Shot_2016-02-16_at_12.16.39_original.png","hires":"http://cdn2.sponsorpay.com/assets/62101/Screen_Shot_2016-02-16_at_12.16.39_original.png"}},{"title":"Catz Target","offer_id":830441,"teaser":"Login and get 150 points to receive your reward.","required_actions":"Login and get 150 points to receive your reward.","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=830441&os_version=4.1&ad_format=offer&group=Fyber&sig=2866250b2bf0112b29d67eae6e699988e233d038","offer_types":[{"offer_type_id":105,"readable":"Registration"}],"payout":12,"time_to_payout":{"amount":300,"readable":"5 minutes"},"thumbnail":{"lowres":"http://cdn1.sponsorpay.com/assets/57842/Catz_Target_square_60.png","hires":"http://cdn1.sponsorpay.com/assets/57842/Catz_Target_square_175.png"}},{"title":"Zombie Hungry WWW","offer_id":779095,"teaser":"Login, get 200 points and stay alive to receive your reward.","required_actions":"Login, get 200 points and stay alive to receive your reward.","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=779095&os_version=4.1&ad_format=offer&group=Fyber&sig=0878549ee466f05548218ae0deb6fbe8178460fc","offer_types":[{"offer_type_id":105,"readable":"Registration"}],"payout":9,"time_to_payout":{"amount":600,"readable":"10 minutes"},"thumbnail":{"lowres":"http://cdn2.sponsorpay.com/assets/58984/Screen_Shot_2015-10-30_at_16.35.03_original.png","hires":"http://cdn2.sponsorpay.com/assets/58984/Screen_Shot_2015-10-30_at_16.35.03_original.png"}},{"title":"Candy Transporter WWW","offer_id":774535,"teaser":"Login and get 200 points to receive your reward.","required_actions":"Login and get 200 points to receive your reward.","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=774535&os_version=4.1&ad_format=offer&group=Fyber&sig=fdbd70e2d0d41325a31e91b285c1e3e1329e9cc3","offer_types":[{"offer_type_id":105,"readable":"Registration"},{"offer_type_id":112,"readable":"Free"}],"payout":6,"time_to_payout":{"amount":300,"readable":"5 minutes"},"thumbnail":{"lowres":"http://cdn3.sponsorpay.com/assets/60599/Screen_Shot_2015-12-18_at_18.33.23_square_60.png","hires":"http://cdn3.sponsorpay.com/assets/60599/Screen_Shot_2015-12-18_at_18.33.23_square_175.png"}},{"title":"Bomb Stars WWW","offer_id":785599,"teaser":"Login and get 250 points to receive your reward.","required_actions":"Login and get 250 points to receive your reward.","link":"http://offer.fyber.com/mobile?impression=true&appid=46453&uid=1&client=api&platform=android&appname=RewardsFox+%28Android%29&traffic_source=offer_api&country_code=LB&pubid=115996&ip=77.42.171.88&pub0=campaign3&device_id=2b6f0cc904d137be2e1730235f5664094b831186&google_ad_id=cff26c67f527e6817bf6935c54f8cc5cc5cffac2&ad_id=785599&os_version=4.1&ad_format=offer&group=Fyber&sig=b16cd45f8b63a9dff9b17a8c1d5b806945e16990","offer_types":[{"offer_type_id":105,"readable":"Registration"},{"offer_type_id":112,"readable":"Free"}],"payout":19,"time_to_payout":{"amount":600,"readable":"10 minutes"},"thumbnail":{"lowres":"http://cdn2.sponsorpay.com/assets/60791/Screen_Shot_2015-12-30_at_13.24.29_square_60.png","hires":"http://cdn2.sponsorpay.com/assets/60791/Screen_Shot_2015-12-30_at_13.24.29_square_175.png"}}]}';

        $contentObject = json_decode($content);

        return new Offers($contentObject);
    }

    /**
     * @param $appType
     *
     * @return  null
     * @throws \mahmoudz\fyberPhpSdk\WrongAppTypeException
     */
    private function getAppId($appType)
    {
        $appId = null;

        switch (strtolower($appType)) {
            case 'android':
                $appId = $this->android_app_id;
                break;
            case 'ios':
                $appId = $this->android_app_id;
                break;
            case 'web':
                $appId = $this->android_app_id;
                break;
            default:
                throw new WrongAppTypeException('Supported App types are ("android", "ios" and "web").');
        }

        return $appId;
    }

    /**
     * @param $data
     *
     * @return  array
     */
    private function validateData($data)
    {
        $allPossibleData = [
            'appid'                                 => null,
            'uid'                                   => null,
            'locale'                                => null,
            'device_id'                             => null,
            'os_version'                            => null,
            'timestamp'                             => null,
            'ip'                                    => null,
            'pub0'                                  => null,
            'page'                                  => null,
            'offer_types'                           => null,
            'ps_time'                               => null,
            'device'                                => null,
            'google_ad_id'                          => null,
            'google_ad_id_limited_tracking_enabled' => null,
            'apple_idfa'                            => null,
            'apple_idfa_tracking_enabled'           => null,
        ];

        // Fill missing data input with null, by merging it with the $allPossibleData
        $data = array_replace_recursive($allPossibleData, $data);

        // remove all null values, in case provided by the input or was missing form the input
        $data = array_filter($data);

        $required = ['appid', 'uid', 'locale', 'device_id', 'os_version', 'timestamp'];

        if (!$this->requiredFieldsExist($required, $data)) {
            throw new MissingRequiredDataException();
        }

        return $data;
    }

    /**
     * @param $required
     * @param $data
     *
     * @return  bool
     */
    private function requiredFieldsExist($required, $data)
    {
        if (count(array_intersect_key(array_flip($required), $data)) === count($required)) {
            //All required keys exist!
            return true;
        }

        return false;
    }

    /**
     * @param array $parametersArray
     *
     * @return  string
     */
    private function calculateHashKey(Array $parametersArray)
    {
        // 1. Order all request alphabetically
        ksort($parametersArray);

        // 2. Concatenate all request parameters
        $parametersQuery = http_build_query($parametersArray);

        if (!$this->api_key) {
            throw new MissingApiKeyException('Missing API KEY');
        }

        // 3. Concatenate the resulting string with the API Key
        $parametersQueryWithKey = $parametersQuery . '&' . $this->api_key;

        // 4. Hash the resulting string using SHA1
        $hashKey = sha1($parametersQueryWithKey);

        return $hashKey;
    }

    /**
     * Read the values from the config file
     */
    private function readConfig()
    {
        // TODO: validate configurations not missed and replace it with null if so
        $this->base_url = $this->config->get('base_url');
        $this->api_version = $this->config->get('api_version');
        $this->response_format = $this->config->get('response_format');
        $this->api_key = $this->config->get('api_key');

        $this->android_app_id = $this->config->get('android_app_id');
        $this->ios_app_id = $this->config->get('ios_app_id');
        $this->web_app_id = $this->config->get('web_app_id');
    }


    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->api_key;
    }

    /**
     * with this you can override the config key
     *
     * @param  mixed $key
     */
    public function setKey($key)
    {
        $this->api_key = $key;

        return $this;
    }

}
