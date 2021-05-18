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

            $zoho_module = ZohoManager::useModule('Contacts');

            $record = $zoho_module->getRecordInstance();

            $record->setFieldValue('Phone', $phone);

            $record->setFieldValue('First_Name', $this->user->first);

            $record->setFieldValue('Last_Name', $this->user->first);

            $record->setFieldValue('Email', $this->user->email);

            $record->setFieldValue('Client_status', 'Active');

            $record->setFieldValue('Client_status', 'Pending activation');

            $entity = $record->create();

            if (empty($this->user->zoho_id)) {

                $entity = $record->create();

                $this->user->zoho_id = $entity->getData()->getEntityId();

                $this->user->save();

            } else {
                $record->update();
            }

    }


}
