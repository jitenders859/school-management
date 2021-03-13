<?php

// LEO CLIENT
// client id 243395394461-fh9i9vgs7l3ubqm74ariasbb9r72lur4.apps.googleusercontent.com
// client secret akpsvO9G_-j1ry36jQIcVmax
// project leo chip


// KERJOY CLIENT
// cleint Id 74849830692-ve1cqp5v5snpmescc3d72roilv0sabji.apps.googleusercontent.com
// client secret lyucRAeG6MLnGVDQrfxmyIFF

class Upload extends Trongate {

    function index() {
        $data['title'] = $this->input('title');
        $data['description'] = $this->input('description');
        $data['form_location'] =  str_replace('upload', 'upload/submit', current_url());
        $data['headline'] = "Upload video to youtube";
        $data['view_module'] = "upload";
        $data['view_file'] = 'index';
        $this->template('public_milligram', $data);
    }

    function is_table_empty() {
        $result = $this->model->get("id", "token");
        if($result == false) {
            return true;
        }

        return false;
    }


    function callback($update) {


        try {
            $adapter = $this->config();
            $adapter->authenticate();
            $token = $adapter->getAccessToken();
            if($this->is_table_empty()) {
                $this->update_access_token(json_encode($token));
            }
            echo "Access token inserted successfully.";
        }
        catch( Exception $e ){
            echo $e->getMessage() ;
        }
    }

     function update_access_token($token) {
        $data['access_token'] = $token;

        $result = $this->model->get("id", 'token', 1, 0);

        if($result == false) {
            $this->model->insert($data, 'token');
        } else {
            $this->model->update($result[0]->id, $data, 'token');
        }
    }



    function config() {
        $config = [
            'callback' => 'http://localhost/sca/upload/callback/',
            'keys'     => [
                            'id' => GOOGLE_CLIENT_ID,
                            'secret' => GOOGLE_CLIENT_SECRET
                        ],
            'scope'    => 'https://www.googleapis.com/auth/youtube https://www.googleapis.com/auth/youtube.upload',
            'authorize_url_parameters' => [
                    'approval_prompt' => 'force', // to pass only when you need to acquire a new refresh token.
                    'access_type' => 'offline'
            ]
        ];

        $adapter = new Hybridauth\Provider\Google( $config );

        return $adapter;
    }

    function get_access_token() {
        $result = $this->model->get("id", 'token', 1, 0);

        if($result != false) {
            return json_decode($result['0']->access_token);
        }
        return;
    }

     function get_refersh_token() {
        $result = $this->get_access_token();
        return $result->refresh_token;
    }


    function submit() {

        if (isset($_POST['submit'])) {
            $arr_data = array(
                'title' => $this->input('title', true),
                'summary' => $this->input('summary', true),
                'video_path' => $_FILES['file']['tmp_name'],
            );
           $this->upload_video_on_youtube($arr_data);
        }
    }

    function upload_video_on_youtube($arr_data) {

        $client = new Google_Client();


        $arr_token = (array) $this->get_access_token();

        $accessToken = array(
            'access_token' => $arr_token['access_token'],
            'expires_in' => $arr_token['expires_in'],
        );

        $client->setAccessToken($accessToken);

        $service = new Google_Service_YouTube($client);

        $video = new Google_Service_YouTube_Video();

        $videoSnippet = new Google_Service_YouTube_VideoSnippet();
        $videoSnippet->setDescription($arr_data['summary']);
        $videoSnippet->setTitle($arr_data['title']);
        $video->setSnippet($videoSnippet);

        $videoStatus = new Google_Service_YouTube_VideoStatus();
        $videoStatus->setPrivacyStatus('public');
        $video->setStatus($videoStatus);

        try {
            $response = $service->videos->insert(
                'snippet,status',
                $video,
                array(
                    'data' => file_get_contents($arr_data['video_path']),
                    'mimeType' => 'video/*',
                    'uploadType' => 'multipart'
                )
            );
            echo "Video uploaded successfully. Video id is ". $response->id;
        } catch(Exception $e) {
            if( 401 == $e->getCode() ) {
                $refresh_token = $this->get_refersh_token();

                $client = new GuzzleHttp\Client(['base_uri' => 'https://accounts.google.com']);

                $response = $client->request('POST', '/o/oauth2/token', [
                    'form_params' => [
                        "grant_type" => "refresh_token",
                        "refresh_token" => $refresh_token,
                        "client_id" => GOOGLE_CLIENT_ID,
                        "client_secret" => GOOGLE_CLIENT_SECRET,
                    ],
                ]);

                $data = (array) json_decode($response->getBody());
                $data['refresh_token'] = $refresh_token;

                $this->update_access_token(json_encode($data));

                $this->upload_video_on_youtube($arr_data);
            } else {
                //echo $e->getMessage(); //print the error just in case your video is not uploaded.
            }
        }
    }

    function upload_thumbnail($response) {
        $arr_data = array(
            'title' => $_POST['title'],
            'summary' => $_POST['summary'],
            'video_path' => $_FILES['file']['tmp_name'],
            'image_path' => $_FILES['image']['tmp_name'], // here we are passing image
        );


        echo "Video uploaded successfully. Video id is ". $response->id;
        $client = new Google_Client();
        $service = new Google_Service_YouTube($client);
//upload thumbnail
            $videoId = $response->id;

            $chunkSizeBytes = 1 * 1024 * 1024;

            $client->setDefer(true);

            $setRequest = $service->thumbnails->set($videoId);

            $media = new Google_Http_MediaFileUpload(
                $client,
                $setRequest,
                'image/png',
                null,
                true,
                $chunkSizeBytes
            );
            $imagePath = $arr_data['image_path'];
            $media->setFileSize(filesize($imagePath));

            $status = false;
            $handle = fopen($imagePath, "rb");

            while (!$status && !feof($handle)) {
                $chunk  = fread($handle, $chunkSizeBytes);
                $status = $media->nextChunk($chunk);
            }

            fclose($handle);

            $client->setDefer(false);
            echo "<br>Thumbanil: ". $status['items'][0]['default']['url'];
                }

    function delete_video($id) {

        $client = new Google_Client();

        $arr_token = (array) $this->get_access_token();
        $accessToken = array(
            'access_token' => $arr_token['access_token'],
            'expires_in' => $arr_token['expires_in'],
        );

        try {
            $client->setAccessToken($accessToken);
            $service = new Google_Service_YouTube($client);
            $service->videos->delete($id);
        } catch(Exception $e) {
            if( 401 == $e->getCode() ) {
                $refresh_token = $this->get_refersh_token();

                $client = new GuzzleHttp\Client(['base_uri' => 'https://accounts.google.com']);

                $response = $client->request('POST', '/o/oauth2/token', [
                    'form_params' => [
                        "grant_type" => "refresh_token",
                        "refresh_token" => $refresh_token,
                        "client_id" => GOOGLE_CLIENT_ID,
                        "client_secret" => GOOGLE_CLIENT_SECRET,
                    ],
                ]);

                $data = (array) json_decode($response->getBody());
                $data['refresh_token'] = $refresh_token;

                $this->update_access_token(json_encode($data));

                $this->delete_video($id);
            } else {
                //echo $e->getMessage(); //print the error just in case your video is not uploaded.
            }
        }
    }


}





?>