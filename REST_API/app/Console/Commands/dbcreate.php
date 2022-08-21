<?php

namespace App\Console\Commands;

use App\Application;
use Illuminate\Console\Command;
use PDO;
use PDOException;
use mysqli;

class dbcreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create database usig configuration';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $database = env('DB_DATABASE', false);
        if (! $database) {
            $this->info('Skipping creation of database as env(DB_DATABASE) is empty');
            return;
        }

        $host = env('DB_HOST');
        $user = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $port = env('DB_PORT');


        if (env('DB_CONNECTION', 'mysql') == 'mysql')
        {
            $mysqli = new mysqli($host, $user, $password, null, $port);
            if ($mysqli->connect_errno) {
                echo "Fallo al conectar a MySQL: " . $mysqli->connect_error;
                exit();
            }

            if ($mysqli->query("CREATE SCHEMA $database DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ;") === TRUE) {
                printf("Database '$database' created.\n");
            } else {
                echo $mysqli->error;
            }

            $mysqli->close();
        }
        else
        {
            echo 'This command only works with mysql, and in your config file you use ' . $_ENV['DB_CONNECTION'] . '\n';
            echo 'modify dbcreate.php in App\Commands to add the creation of your database handler.';
        }

        return 0;
    }
}
