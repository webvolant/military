<?php
class ControllerModuleBay extends Controller {
    public function index() {
        $this->load->language('module/bay');

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_positive'] = $this->language->get('text_positive');
        $data['text_score'] = $this->language->get('text_score');
        $data['text_regdate'] = $this->language->get('text_regdate');
        //$this->load->language('module/google_hangouts');

        //$data['heading_title'] = $this->language->get('heading_title');
        /*
                if ($this->request->server['HTTPS']) {
                    $data['code'] = str_replace('http', 'https', html_entity_decode($this->config->get('google_hangouts_code')));
                } else {
                    $data['code'] = html_entity_decode($this->config->get('google_hangouts_code'));
                }

                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/google_hangouts.tpl')) {
                    return $this->load->view($this->config->get('config_template') . '/template/module/google_hangouts.tpl', $data);
                } else {
                    return $this->load->view('default/template/module/google_hangouts.tpl', $data);
                }*/
        $sellerID = "discount*portal";
        /*$url = "http://open.api.ebay.com/shopping?
        callname=GetUserProfile&
        responseencoding=XML&
        appid=Barkalov-1539-44b7-ba1f-17da6a749a23&
        siteid=0&
        version=525&
        UserID=discount*portal";*/

        //if ($debug) { print "<br />GetUserProfile call = <blockquote>$apicall </blockquote>"; }
        $results="";
        // Load the call and capture the document returned by the Shopping API
        $resp = simplexml_load_file("http://open.api.ebay.com/shopping?callname=GetUserProfile&responseencoding=XML&appid=Barkalov-1539-44b7-ba1f-17da6a749a23&siteid=0&version=525&UserID=$sellerID");
        $data['results'] = array();
        if (!empty($resp)) {
            if (!empty($resp->User->MyWorldLargeImage)) {
                $myWorldImgURL = $resp->User->MyWorldLargeImage;
            } else {
                $myWorldImgURL = 'http://pics.ebaystatic.com/aw/pics/community/myWorld/imgBuddyBig1.gif';
            }
            $data['results'][] = array(
                'seller'            =>$sellerID,
                'feed_url'          =>$resp->User->FeedbackDetailsURL,
                'positive_percent'  =>$resp->User->PositiveFeedbackPercent,
                'score'             =>$resp->User->FeedbackScore . "<i class='fa fa-star red'></i>",
                'reg_date'          =>substr($resp->User->RegistrationDate, 0, 10),
            );
            /*"<table><tr>\n";
            //$results .= "<td><a href=\"" . $resp->User->MyWorldURL . "\"><img src=\""
                //. $myWorldImgURL . "\"></a></td>\n";
            $results .= "<td>Seller : $sellerID <br /> \n";
            $icon = $resp->User->FeedbackRatingStar;
            $results .= "Feedback score : " . $resp->User->FeedbackScore . "<i class='fa fa-star red'></i>"."<br />\n";
            //$results .= "Pos score : " . $resp->FeedbackHistory->UniquePositiveFeedbackCount;
            //$results .= "Neg score : " . $resp->FeedbackHistory->UniqueNegativeFeedbackCount;
            //$results .= "Neg score : " . $resp->FeedbackHistory->UniqueNeutralFeedbackCount;
            //$posFeedBackPct = sprintf("%01.1f", (100 * ($posCount / ($posCount + $negCount))));
            $results .= "Positive feedback :" . $resp->User->PositiveFeedbackPercent. "%<br />\n";
            $regDate = substr($resp->User->RegistrationDate, 0, 10);
            $results .= "Registration date : $regDate<br />\n";
            $results .= "</tr></table>\n";*/
        } else {
            //$results = "<h3>No user profile for seller $sellerID";
        }
        //$data = $results;
        //return $this->load->view('default/template/module/ebay_profile.tpl', $data);
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/bay.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/bay.tpl', $data);
        } else {
            return $this->load->view('default/template/module/bay.tpl', $data);
        }
    }
}