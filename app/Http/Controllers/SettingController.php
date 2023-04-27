<?php

namespace App\Http\Controllers;

require_once(__DIR__."/../../../vendor/autoload.php");

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use YConnect\Constant\OIDConnectDisplay;
use YConnect\Constant\OIDConnectPrompt;
use YConnect\Constant\OIDConnectScope;
use YConnect\Constant\ResponseType;
use YConnect\Credential\ClientCredential;
use YConnect\YConnectClient;
use App\Models\User;

class SettingController extends Controller
{
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('setting.index', [
            'my' => User::find(auth()->id())
        ]);
    }
    /**
     * Yahoo認証
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request)
    {
        $params = $request->all();

        if ($params["act"] === "yahoo_auth") {
            
            $validator = Validator::make($request->all(), [
                'yahoo_store_account' => ['required', 'string', 'max:255'],
                'yahoo_client_id' => ['required', 'string', 'max:255'],
                'yahoo_secret' => ['required', 'string', 'max:255']
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $yahoo_client_id = $params['yahoo_client_id'];
            $yahoo_secret = $params['yahoo_secret'];

            $cred = new ClientCredential($yahoo_client_id, $yahoo_secret);
            $client = new YConnectClient($cred);

            $state = $this->generateRandomString(36);
            $nonce = $this->generateRandomString(52);

            $my = User::find(auth()->id());
            $my->yahoo_store_account = $params['yahoo_store_account'];
            $my->yahoo_client_id = $params['yahoo_client_id'];
            $my->yahoo_secret = $params['yahoo_secret'];
            $my->yahoo_state = $state;
            $my->yahoo_nonce = $nonce;
            $my->save();

            $redirect_uri = route('yahoo_callback');

            $response_type = ResponseType::CODE;
            $scope = array(
                OIDConnectScope::OPENID,
                OIDConnectScope::PROFILE,
                OIDConnectScope::EMAIL,
                OIDConnectScope::ADDRESS
            );
            $display = OIDConnectDisplay::DEFAULT_DISPLAY;
            $prompt = array(
                OIDConnectPrompt::DEFAULT_PROMPT
            );

            $client->requestAuth(
                $redirect_uri,
                $state,
                $nonce,
                $response_type,
                $scope,
                $display,
                $prompt
            );
        }
    }

    public function yahooCallBack(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'state' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $params = $request->all();
        $yahoo_state = $params['state'];

        $my = User::where('yahoo_state', $yahoo_state)->first();

        $yahoo_client_id = $my->yahoo_client_id;
        $yahoo_secret = $my->yahoo_secret;

        $cred = new ClientCredential($yahoo_client_id, $yahoo_secret);
        $client = new YConnectClient($cred);

        $state = $my->yahoo_state;
        $nonce = $my->yahoo_nonce;
        $redirect_uri = route('yahoo_callback');

        $code_result = $client->getAuthorizationCode($state);
        if( $code_result ) {
            // Tokenエンドポイントにリクエスト
            $client->requestAccessToken(
                $redirect_uri,
                $code_result
            );
            try {
                // IDトークンを検証(4)
                $accessToken  = $client->getAccessToken();
                $client->verifyIdToken($nonce, $accessToken);
                $refreshToken = $client->getRefreshToken();
                $yahoo_access_token_expires_in = date("Y-m-d H:i:s", $client->getAccessTokenExpiration() + time());
                $yahoo_refresh_token_expires_in = date("Y-m-d H:i:s", strtotime("+25 day"));
                //トークンの保存
                $my->yahoo_access_token = $accessToken;
                $my->yahoo_refresh_token = $refreshToken;
                $my->yahoo_access_token_expires_in = $yahoo_access_token_expires_in;
                $my->yahoo_refresh_token_expires_in = $yahoo_refresh_token_expires_in;
                $my->save();

                return redirect()->route('setting.index');
            } catch (Exception $e ) {
                echo "認証失敗。";
            }
            return ("failed");
        }
    }
}
