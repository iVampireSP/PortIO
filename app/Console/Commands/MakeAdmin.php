<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make-admin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '将一个用户升级为管理员，需要提供用户的邮箱。';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $email = $this->argument('email');

        $user = (new User)->where('email', $email)->first();

        if ($user) {

            if (!$user->is_admin) {
                $user->is_admin = true;
                $user->save();
                $this->info('用户已经升级为管理员。需要取消管理员身份请使用再次执行此命令。');
                return;
            }

            $cancel = $this->confirm('用户已经升级为管理员，要取消管理员身份吗？', true);

            if ($cancel) {
                $user->is_admin = false;
                $user->save();
                $this->info('用户已经取消管理员身份。');
            } else {
                $this->info('用户仍然是管理员。');
            }

        } else {
            $this->error('用户不存在。');
        }
    }
}
