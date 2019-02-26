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

        Schema::create("clients", function (Blueprint $table){
            $table->increments("id");
            $table->string("name");
            $table->string("email");
            $table->string("username");
            $table->string("password");
            $table->string("company_name");
            $table->text("description");
            $table->string("sub_domain");
            $table->integer("status")->default(0);
            $table->timestamps();
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
        Schema::dropIfExists("databases");
        Schema::dropIfExists("ingresses");
        Schema::dropIfExists("deployment_pvc");
        Schema::dropIfExists("services");
        Schema::dropIfExists("deployments");
        Schema::dropIfExists("clients");
    }
}
