<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\OldModels\OldLocation;
use App\Models\OldModels\OldUser;
use App\Models\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ImporterExtension extends Command
{
    public const CREATOR_EDITOR_ID = 1;
    protected $signature = 'importer:extension';
    protected $description = 'Import data does not exist on old importer';
    private ProgressBar $bar;


    public function __construct()
    {
        parent::__construct();
    }

    public function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->info('Import data is started 👌');
    }


    public function handle()
    {
        $this->importUsers();
        $this->newLine(2);
        $this->info('Import data is finished 👌');
    }

    private function importUsers()
    {
        User::unguard(true);

        $dateOfUpgrade = '2021-05-22';
        $lastUserImportedId = 11757;
        $oldUsers = OldUser::whereRaw('date(created_at) >= date_sub(date(?), INTERVAL 10 DAY)', $dateOfUpgrade)
                           ->whereRaw('date(created_at) <= date(?)', $dateOfUpgrade)
                           ->whereNotIn('status', [OldUser::STATUS_PENDING])
                           ->where('id', '>', $lastUserImportedId)
                           ->get();
        $this->newLine();
        $this->bar = $this->output->createProgressBar($oldUsers->count());
        $this->bar->start();
        foreach ($oldUsers as $oldUser) {
            $this->insertUser($oldUser);
            $this->bar->advance();
        }
        $this->bar->finish();
    }

    private function insertUser(OldUser $oldUser)
    {
        $tempUser = [];
        foreach ($oldUser->attributesComparing() as $oldModelKey => $newModelKey) {
            $tempUser[$newModelKey] = $oldUser->{$oldModelKey};
        }
        $tempUser['status'] = $this->getOldUserStatus($oldUser);
        try {
            if (isset($tempUser['id'])) {
                unset($tempUser['id']);
            }
            $freshUser = $this->workOnNewUser($tempUser, $oldUser);
            $this->newLine();
            $this->line('$freshUser '. $freshUser->id);
            $this->newLine();
        } catch (\Exception $e) {
            info('___First attribute:'.\Arr::first($tempUser), [$e->getMessage()]);
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            if ($errorCode == 1062 && \Str::contains($errorMessage, 'users_username_unique')) {
                try {
                    $tempUser['username'] = $this->getUuidString();
                    $freshUser = $this->workOnNewUser($tempUser, $oldUser);
                    $this->newLine();
                    $this->line('Try $freshUser '. $freshUser->id);
                    $this->newLine();
                } catch (\Exception $e) {
                    $errorMessage = $e->getMessage().'___id:'.$tempUser['id'].PHP_EOL;
                    \Log::error('$errorMessage is: '.$errorMessage);
                    $this->newLine(2);
                    $this->error('Check the log.');
                    $this->newLine(2);
                }
            }
        }
    }

    private function getOldUserStatus(OldUser $oldUser): int
    {
        if ($oldUser->status === OldUser::STATUS_ACTIVE) {
            $status = User::STATUS_ACTIVE;
            if ($oldUser->type === 'DRIVER' && $oldUser->sub_type !== 'APP_DRIVER') {
                $status = User::STATUS_INACTIVE;
            }
        } else {
            $status = User::STATUS_INACTIVE;
        }

        return $status;
    }

    private function getUuidString($min = 10, $max = 99): string
    {
        return Controller::uuid().mt_rand($min, $max);
    }


    private function importAddresses($oldLocations, User $user)
    {
        foreach ($oldLocations as $oldLocation) {
            if (is_null($oldLocation->deleted_at)) {
                $tempLocation = [];
                if (filter_var($oldLocation->email, FILTER_VALIDATE_EMAIL)) {
                    $tempLocation['emails'] = json_encode([$oldLocation->email]);
                }
                foreach ($oldLocation->attributesComparing() as $oldModelKey => $newModelKey) {
                    $tempLocation[$newModelKey] = $oldLocation->{$oldModelKey};
                }
                $tempLocation['creator_id'] = self::CREATOR_EDITOR_ID;
                $tempLocation['editor_id'] = self::CREATOR_EDITOR_ID;
                $tempLocation['phones'] = json_encode([$oldLocation->phones]);
                $tempLocation['is_default'] = $oldLocation->defualt === OldLocation::IS_DEFAULT ? 1 : 0;
                $tempLocation['status'] = $oldLocation->status === OldLocation::STATUS_ACTIVE ? Location::STATUS_ACTIVE : Location::STATUS_INACTIVE;
                $tempLocation['contactable_id'] = $user->id;
                try {
                    if (isset($tempLocation['id'])) {
                        unset($tempLocation['id']);
                    }
                    $isCreated = Location::create($tempLocation);
                } catch (\Exception $e) {
                    dd($e->getMessage(), PHP_EOL, $tempLocation);
                }
            }
        }
    }

    private function workOnNewUser(array $tempUser, OldUser $oldUser): User
    {
        $freshUser = User::create($tempUser);
        $freshUser->assignRole($oldUser->role_name);
        $this->importAddresses($oldUser->addresses, $freshUser);

        return $freshUser;
    }


}
