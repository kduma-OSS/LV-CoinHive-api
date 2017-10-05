<?php
namespace KDuma\CoinHive;

use Zttp\Zttp;

/**
 * Class CoinHiveApi
 * @package KDuma\CoinHive
 */
class CoinHiveApi
{
    /**
     * CoinHive HTTP API Endpoint
     */
    const API_ENDPOINT = 'https://api.coinhive.com';

    /**
     * @var string
     */
    protected $site_key;

    /**
     * @var string
     */
    protected $secret_key;

    /**
     * Coin Hive Api
     *
     * @param $site_key
     * @param $secret_key
     */
    public function __construct($site_key, $secret_key)
    {
        $this->site_key = $site_key;
        $this->secret_key = $secret_key;
    }

    /**
     * Get the current payout rate and stats about the network.
     *
     * @return array
     */
    public function getPayoutStats()
    {
        return Zttp::get(self::API_ENDPOINT . '/stats/payout', ['secret' => $this->secret_key])->json();
    }

    /**
     * Get the current hashrate, total hashes, paid & pending xmr, and the hourly history for the past seven days for the site.
     *
     * @return array
     */
    public function getSiteStats()
    {
        return Zttp::get(self::API_ENDPOINT . '/stats/site', ['secret' => $this->secret_key])->json();
    }

    /**
     * Withdraw a number of hashes for a user name. If successful, the requested amount will be subtracted from the user's balance.
     *
     * @param string $name The user's name, analogous to the name specified for the CoinHive.User miner.
     * @param integer $amount The amount of hashes to withdraw.
     *
     * @return array
     */
    public function withdrawFromUser($name, $amount)
    {
        return Zttp::asFormParams()->post(self::API_ENDPOINT . '/user/withdraw', [
            'secret' => $this->secret_key,
            'name' => $name,
            'amount' => $amount
        ])->json();
    }

    /**
     * Get the total number of hashes, the withdrawn hashes and the current balance for a user name.
     * Think of it as the balance of a bank account. Hashes can be paid in through mining, and withdrawn through /user/withdraw.
     *
     * @param string $name The user's name, analogous to the name specified for the CoinHive.User miner.
     * This can be anything that is unique to the user on your website.
     * E.g. a user name, id, the md5 hash of their name or their email address.
     *
     * @return array
     */
    public function getUserBalance($name)
    {
        return Zttp::get(self::API_ENDPOINT . '/user/balance', [
            'secret' => $this->secret_key,
            'name' => $name
        ])->json();
    }

    /**
     * Get a list of top users ordered by total number of hashes.
     *
     * @param int $count Optional. The number of users to return. Default 128, min 1, max 1024.
     *
     * @return array
     */
    public function getTopUsers($count = 128)
    {
        return Zttp::get(self::API_ENDPOINT . '/user/top', [
            'secret' => $this->secret_key,
            'count' => $count
        ])->json();
    }

    /**
     * Get a paginated list of all users in alphabetical order. Note that this will only return users with a total number of hashes greater than 0.
     *
     * @param int $page Optional. The page of users to return, obtained from the previous request's nextPage property. Leave out or specify an empty string for the first page.
     * @param int $count Optional. The number of users to return. Default 4096, min 32, max 8192.
     *
     * @return array
     */
    public function getUsersList($page = null, $count = 4096)
    {
        return Zttp::get(self::API_ENDPOINT . '/user/list', [
            'secret' => $this->secret_key,
            'count' => $count,
            'page' => $page,
        ])->json();
    }

    /**
     * Create a new shortlink. You can also do this by hand, directly from your dashboard.
     *
     * @param string $url The target URL for the shortlink.
     * @param int $hashes The number of hashes that have to be solved, before the user is redirected to the target URL.
     *
     * @return array
     */
    public function createLink($url, $hashes = 256)
    {
        return Zttp::asFormParams()->post(self::API_ENDPOINT . '/link/create', [
            'secret' => $this->secret_key,
            'url' => $url,
            'hashes' => $hashes
        ])->json();
    }

    /**
     * Reset a user's total hashes and withdrawn amount to 0.
     *
     * @param string $name The user's name whose total and withdrawn values will be reset to 0.
     *
     * @return array
     */
    public function resetUser($name)
    {
        return Zttp::asFormParams()->post(self::API_ENDPOINT . '/user/reset', [
            'secret' => $this->secret_key,
            'name' => $name
        ])->json();
    }

    /**
     * Reset the hashes and withdrawn amount for all users for this site to 0.
     *
     * @return array
     */
    public function resetAllUsers()
    {
        return Zttp::asFormParams()->post(self::API_ENDPOINT . '/user/reset-all', [
            'secret' => $this->secret_key
        ])->json();
    }

    /**
     * Verify that a token from a CoinHive.Token miner has reached a number of hashes. Tokens are only valid for 1 hour.
     * Note that a token can only be verified once. All subsequent requests to verify the same token will result in the invalid_token error.
     *
     * @param string $token The name of the token you want to verify. This can be obtained directly from the miner, through miner.getToken(). For the captcha, the token name will be submitted together with the form as coinhive-captcha-token.
     * @param int $hashes The number of hashes this token must have reached in order to be valid.
     *
     * @return mixed
     */
    public function verifyToken($token, $hashes = 256)
    {
        return Zttp::asFormParams()->post(self::API_ENDPOINT . '/token/verify', [
            'secret' => $this->secret_key,
            'token' => $token,
            'hashes' => $hashes
        ])->json();
    }
}