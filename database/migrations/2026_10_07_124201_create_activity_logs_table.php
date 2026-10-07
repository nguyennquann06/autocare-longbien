<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(
            'activity_logs',
            function (Blueprint $table) {
                $table->id();


                /*
                |--------------------------------------------------------------------------
                | ACTOR
                |--------------------------------------------------------------------------
                |
                | user_id có thể null trong trường hợp
                | một tác vụ hệ thống chạy tự động.
                |
                */

                $table
                    ->foreignId(
                        'user_id'
                    )
                    ->nullable()
                    ->constrained(
                        'users'
                    )
                    ->nullOnDelete();


                /*
                |--------------------------------------------------------------------------
                | ROLE SNAPSHOT
                |--------------------------------------------------------------------------
                |
                | Lưu role tại đúng thời điểm thao tác.
                |
                | Nếu sau này STAFF đổi thành TECHNICIAN,
                | nhật ký cũ vẫn biết lúc đó người này
                | đang thao tác với quyền STAFF.
                |
                */

                $table
                    ->string(
                        'role_code',
                        30
                    )
                    ->nullable();


                /*
                |--------------------------------------------------------------------------
                | ACTION
                |--------------------------------------------------------------------------
                |
                | Ví dụ:
                |
                | USER_CREATED
                | USER_ROLE_CHANGED
                | APPOINTMENT_STATUS_CHANGED
                | SERVICE_ORDER_CREATED
                | TECHNICIAN_REASSIGNED
                | PART_STOCK_IN
                | SERVICE_ORDER_STARTED
                | SERVICE_ITEM_STATUS_CHANGED
                | SERVICE_ORDER_COMPLETED
                | INVOICE_CREATED
                | INVOICE_PAID
                |
                */

                $table
                    ->string(
                        'action',
                        80
                    );


                /*
                |--------------------------------------------------------------------------
                | SUBJECT / ENTITY
                |--------------------------------------------------------------------------
                |
                | Không dùng foreign key vì log có thể tham chiếu
                | nhiều loại model khác nhau.
                |
                */

                $table
                    ->string(
                        'entity_type',
                        100
                    )
                    ->nullable();


                $table
                    ->unsignedBigInteger(
                        'entity_id'
                    )
                    ->nullable();


                /*
                |--------------------------------------------------------------------------
                | HUMAN DESCRIPTION
                |--------------------------------------------------------------------------
                */

                $table
                    ->text(
                        'description'
                    );


                /*
                |--------------------------------------------------------------------------
                | AUDIT DATA
                |--------------------------------------------------------------------------
                */

                $table
                    ->json(
                        'old_values'
                    )
                    ->nullable();


                $table
                    ->json(
                        'new_values'
                    )
                    ->nullable();


                /*
                |--------------------------------------------------------------------------
                | REQUEST CONTEXT
                |--------------------------------------------------------------------------
                */

                $table
                    ->string(
                        'ip_address',
                        45
                    )
                    ->nullable();


                $table
                    ->string(
                        'user_agent',
                        1000
                    )
                    ->nullable();


                $table->timestamps();


                /*
                |--------------------------------------------------------------------------
                | INDEXES
                |--------------------------------------------------------------------------
                */

                $table->index(
                    'role_code'
                );


                $table->index(
                    'action'
                );


                $table->index([
                    'entity_type',
                    'entity_id',
                ]);


                $table->index(
                    'created_at'
                );
            }
        );
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(
            'activity_logs'
        );
    }
};