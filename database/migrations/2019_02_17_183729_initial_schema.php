<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitialSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create("clusters", function (Blueprint $table){
           $table->increments("id");
           $table->string("name");
           $table->string("ip_address");
           $table->string("cluster_id");
           $table->timestamps();
        });

        Schema::create("images", function (Blueprint $table){
            $table->increments("id");
            $table->string("name");
            $table->string("dockerhub_image");
            $table->string("sql_file");
            $table->timestamps();
        });

        Schema::create("clients", function (Blueprint $table){
            $table->increments("id");
            $table->string("name");
            $table->string("email");
            $table->string("company_name");
            $table->text("description");
            $table->string("sub_domain");
            $table->boolean("status")->default(false);
            $table->boolean("database_status")->default(false);
            $table->integer("cluster_id")->unsigned();
            $table->integer("image_id")->unsigned();
            $table->timestamps();

            $table->foreign("cluster_id")->references("id")->on("clusters");
            $table->foreign("image_id")->references("id")->on("images");
        });

        Schema::create("deployments", function (Blueprint $table){
            $table->increments("id");
            $table->integer("client_id")->unsigned();
            $table->integer("replicas");
            $table->string("name");
            $table->string("label");
            $table->timestamps();

            $table->foreign("client_id")->references("id")->on("clients");
        });

        Schema::create("services", function (Blueprint $table){
            $table->increments("id");
            $table->integer("client_id")->unsigned();
            $table->integer("deployment_id")->unsigned();
            $table->string("name");
            $table->string("label");
            $table->timestamps();

            $table->foreign("client_id")->references("id")->on("clients");
            $table->foreign("deployment_id")->references("id")->on("deployments");
        });

        Schema::create("deployment_pvc", function (Blueprint $table){
            $table->increments("id");
            $table->integer("client_id")->unsigned();
            $table->integer("deployment_id")->unsigned();
            $table->string("name");
            $table->string("storage");
            $table->timestamps();

            $table->foreign("client_id")->references("id")->on("clients");
            $table->foreign("deployment_id")->references("id")->on("deployments");
        });

        Schema::create("ingresses", function (Blueprint $table){
            $table->increments("id");
            $table->integer("client_id")->unsigned();
            $table->integer("service_id")->unsigned();
            $table->string("name");
            $table->string("sub_domain");
            $table->string("resource");
            $table->timestamps();

            $table->foreign("client_id")->references("id")->on("clients");
            $table->foreign("service_id")->references("id")->on("services");
        });

        Schema::create("databases", function (Blueprint $table){
            $table->increments("id");
            $table->integer("client_id")->unsigned();
            $table->string("name");
            $table->string("label");
            $table->string("db_database");
            $table->string("db_username");
            $table->string("db_password");
            $table->timestamps();

            $table->foreign("client_id")->references("id")->on("clients");

        });

        Schema::create("database_services", function (Blueprint $table){
            $table->increments("id");
            $table->integer("client_id")->unsigned();
            $table->integer("database_id")->unsigned();
            $table->string("name");
            $table->string("label");
            $table->timestamps();

            $table->foreign("client_id")->references("id")->on("clients");
            $table->foreign("database_id")->references("id")->on("databases");
        });

        Schema::create("database_pvc", function (Blueprint $table){
            $table->increments("id");
            $table->integer("client_id")->unsigned();
            $table->integer("database_id")->unsigned();
            $table->string("storage");
            $table->string("name");
            $table->timestamps();

            $table->foreign("client_id")->references("id")->on("clients");
            $table->foreign("database_id")->references("id")->on("databases");
        });

        \App\User::create([
            'name' => "admin",
            'email' => 'test@test.com',
            'password'=> \Illuminate\Support\Facades\Hash::make("123456")
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists("database_pvc");
        Schema::dropIfExists("database_services");
        Schema::dropIfExists("databases");
        Schema::dropIfExists("ingresses");
        Schema::dropIfExists("deployment_pvc");
        Schema::dropIfExists("services");
        Schema::dropIfExists("deployments");
        Schema::dropIfExists("clients");
        Schema::dropIfExists("images");
        Schema::dropIfExists("clusters");
    }
}
