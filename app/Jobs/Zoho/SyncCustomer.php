<?php

namespace App\Jobs\Zoho;

use App\Integrations\Zoho\ZohoCrm;
use App\Models\User;
use Asciisd\Zoho\Facades\ZohoManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class SyncCustomer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    /**
     * Create a new job instance.
     *
     * @param  User  $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
          (new ZohoCrm($this->user))->createZohoClient();
        }
        catch (\Exception $e){
            info('user_id is :',[
                'user_id' => $this->user->id,
                'error_message' => $e->getMessage()
            ]);
        //    $this->fail();
        }
    }


/*    public function updateZohoEntity(){

        $phone = str_replace('+', '00', $this->user->international_phone);

        $first_name = $this->customer->name;

        $last_name = '.';

        $split = explode(' ', $this->customer->name);

        if ( count($split) > 1 ){

            $first_name = $split[0];

            unset($split[0]);

            $last_name = implode(' ', $split);
        }

        $zoho_module = ZohoManager::useModule('Contacts');

        if (!empty($this->customer->zoho_id)){

            $record =  $zoho_module->getRecord($this->customer->zoho_id);

        }
        else{

            $record =  $zoho_module->getRecordInstance();

        }

        $record->setFieldValue('Phone', $phone);

        $record->setFieldValue('First_Name', $first_name);

        $record->setFieldValue('Last_Name', $last_name);

        $record->setFieldValue('Email', $this->customer->email);

        $record->setFieldValue('Client_status', 'Active');

        // $record->setFieldValue('Client_type', 'Client');

        if (empty($this->customer->zoho_id)){

            $entity = $record->create();

            $this->customer->zoho_id = $entity->getData()->getEntityId();

            $this->customer->save();

        }
        else{

            $record->update();

        }
    }*/

}
