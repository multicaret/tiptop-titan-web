<?php

namespace App\Integrations\Zoho;

use App\Models\Product;
use App\Models\User;
use Asciisd\Zoho\Facades\ZohoManager;
use Illuminate\Support\Facades\Log;

class ZohoCrm
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function createZohoClient(){



        $phone = str_replace('+', '00', $this->user->international_phone);
        $first = empty($this->user->first) ? $this->user->first : 'N/A';
        $last = empty($this->user->last) ? $this->user->last : 'N/A';

        $zoho_module = ZohoManager::useModule('Contacts');

        if (!empty($this->user->zoho_crm_id)){

            $record =  $zoho_module->getRecord($this->user->zoho_crm_id);

        }
        else{

            $record =  $zoho_module->getRecordInstance();

        }
            $record->setFieldValue('Phone', $phone);

            $record->setFieldValue('First_Name', $first);

            $record->setFieldValue('Last_Name', $last);

            $record->setFieldValue('Client_status', 'Active');

            $record->setFieldValue('Client_status', 'Pending activation');


            if (empty($this->user->zoho_crm_id)) {

                $entity = $record->create();

                $this->user->zoho_crm_id = $entity->getData()->getEntityId();

                $this->user->save();

            } else {
                $record->update();
            }

    }


}
