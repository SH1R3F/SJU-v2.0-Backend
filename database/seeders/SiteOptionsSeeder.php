<?php

namespace Database\Seeders;

use App\Models\SiteOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SiteOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $records = [
          [
            'key' => 'sms_settings',
            'value' => [
              [
                'key' => 'Service provider',
                'value' => 0,
              ],
              [
                'key' => 'Username',
                'value' => '966540888009',
              ],
              [
                'key' => 'Password',
                'value' => 'Y1121111211y',
              ],
              [
                'key' => 'Sender',
                'value' => 'SJU.ORG.SA',
              ]
            ]
          ],
          [
            'key' => 'sms_options',
            'value' => [
              [
                'key' => 'Sending SMS after registration',
                'value' => true
              ],
              [
                'key' => 'After registration message',
                'value' => "You have been registered successfully, please complete the required information in order for the membership application to be sent\nSaudi Journalists Association"
              ],
              [
                'key' => 'Sending SMS after finishing information and applying',
                'value' => true
              ],
              [
                'key' => 'After finishing information and applying message',
                'value' => "Your membership request has been sent, we will verify your data\nYou will be contacted if approved\nSaudi Journalists Association"
              ],
              [
                'key' => 'Sending SMS upon acceptance',
                'value' => true
              ],
              [
                'key' => 'Acceptance message',
                'value' => "Membership has been approved, to activate membership, enter the site and pay the required amount\nSaudi Journalists Association"
              ],
              [
                'key' => 'Sending SMS after payment',
                'value' => true
              ],
              [
                'key' => 'After payment message',
                'value' => "Payment completed and membership activated successfully\nWe hope that you will be satisfied with our services\nthank you\nSaudi Journalists Association"
              ]
            ]
          ]
        ];

        collect($records)->each(function( $record ) {
          SiteOption::create($record);
        });

    }
}
