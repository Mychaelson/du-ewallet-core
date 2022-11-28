<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call([
            Accounts\AccountsBanksSeeder::class,
            Accounts\AccountsCountriesSeeder::class,
            Accounts\AccountsJobsSeeder::class,
            Accounts\AccountsRolesSeeder::class,
            Accounts\CompanyBankAccountsSeeder::class,
            Banner\BannerSeeder::class,
            Connect\AppsCredentialSeeder::class,
            Docs\DocsSeeder::class,
            Docs\HelpCategorySeeder::class,
            Docs\HelpSeeder::class,
            Lang\PrjScreenTransLangSeeder::class,
            Lang\PrjScreenTransSeeder::class,
            Lang\ProjectLangguageSeeder::class,
            Lang\ProjectScreenSeeder::class,
            Lang\ProjectSeeder::class,
            Lang\ProjectVersionSeeder::class,
            Bill\Bill::class,
            Cart\Card::class,
            Notifications\Notifications::class,
            Wallet\WalletLabelsSeeder::class,
            Wallet\WalletLimitSeeder::class,
            Wallet\SwitchingFeeBanksSeeder::class,
            Wallet\SiteParamsSeeder::class,
            Settings\SiteParamsSeeder::class,
            Ppob\BillerServiceSeeder::class,
            Ppob\DigitalCategorySeeder::class,
            Ppob\DigitalProductSeeder::class,
            Ppob\DigitalProductServiceSeeder::class,
            Ppob\SettingSeeder::class,
            Ppob\BillerSeeder::class,
            Accounts\AccountsBankInstructionSeeder::class,
            Accounts\AccountsBankInstructionLinesSeeder::class,
            Ppob\ServiceSeeder::class,
            Ppob\DigitalProductV2Seeder::class,
            Ppob\DigitalProductServiceSeederV2::class,
            Accounts\AccountsBankInstructionSeeder::class,
            Accounts\AccountsBankInstructionLinesSeeder::class
        ]);

        Model::reguard();
    }
}
