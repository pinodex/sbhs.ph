<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

namespace App\Providers;

use Silex\Application;
use Silex\ServiceProviderInterface;
use App\Models\Accounts;

class AccountsProvider implements ServiceProviderInterface {

    protected $app, $currentAccount;

    public function register(Application $app) {
        $this->app = $app;

        if ($accoundId = $this->app['session']->get('account')) {
            $this->currentAccount = Accounts::find($accoundId);
        }

        $app['accounts'] = $this;
    }

    public function boot(Application $app) {

    }

    public function verifyCredentials($data) {
        $account = Accounts::where('email', $data['email'])->first();

        if ($account === null) {
            return false;
        }

        if (!password_verify($data['password'], $account->password)) {
            return false;
        }

        if (password_needs_rehash($account->password, PASSWORD_DEFAULT)) {
            $account->password = password_hash($data['password'], PASSWORD_DEFAULT);
            $account->save();
        }

        $this->currentAccount = $account;
        return true;
    }

    public function getCurrentAccount() {
        return $this->currentAccount;
    }

    public function getCurrentAccountId() {
        return $this->currentAccount->id;
    }

    public function create($data) {
        $account = new Accounts($data);
        $account->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $account->save();
    }

    public function edit($id, $data) {
        $account = Accounts::find($id);
        $account->fill($data);

        if ($data['password']) {
            $account->password = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        $account->save();
    }

    public function getAccounts() {
        return Accounts::all();
    }

    public function getById($id) {
        return Accounts::find($id);
    }

    public function getAccountType($i) {
        switch ($i) {
            case 1:
                return 'Administrator';

            case 2:
                return 'Author';
            
            default:
                return 'Unknown';
        }
    }

    public function loginSession() {
        $this->currentAccount->last_login = date('Y-m-d H:i:s');
        $this->currentAccount->save();

        $this->app['session']->set('account', $this->currentAccount->id);
    }

    public function logoutSession() {
        $this->app['session']->remove('account');
        $this->currentAccount = null;
    }

    public function getAccount($id) {
        return Accounts::find($id);
    }

}