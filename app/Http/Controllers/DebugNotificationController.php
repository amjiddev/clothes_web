<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ContactSubmission;
use App\Models\CustomerMeasurement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DebugNotificationController extends Controller
{
    /**
     * Comprehensive debug endpoint - shows everything
     */
    public function fullDebug()
    {
        $debug = [];

        // 1. Database connection check
        try {
            $debug['database_connection'] = [
                'status' => 'connected',
                'database' => DB::connection()->getDatabaseName(),
                'host' => DB::connection()->getConfig('host'),
            ];
        } catch (\Exception $e) {
            $debug['database_connection'] = [
                'status' => 'failed',
                'error' => $e->getMessage(),
            ];
        }

        // 2. Table existence check
        $debug['tables'] = [];
        foreach (['orders', 'customer_measurements', 'contact_submissions', 'users'] as $table) {
            try {
                $exists = DB::table($table)->limit(1)->get();
                $debug['tables'][$table] = [
                    'exists' => true,
                    'total_records' => DB::table($table)->count(),
                ];
            } catch (\Exception $e) {
                $debug['tables'][$table] = [
                    'exists' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }

        // 3. Column checks
        $debug['columns'] = [];
        
        // Orders columns
        try {
            $columns = DB::select("SHOW COLUMNS FROM orders");
            $debug['columns']['orders'] = collect($columns)->pluck('Field')->toArray();
            $debug['columns']['orders_has_is_seen'] = in_array('is_seen', collect($columns)->pluck('Field')->toArray());
        } catch (\Exception $e) {
            $debug['columns']['orders'] = ['error' => $e->getMessage()];
        }

        // Customer measurements columns
        try {
            $columns = DB::select("SHOW COLUMNS FROM customer_measurements");
            $debug['columns']['customer_measurements'] = collect($columns)->pluck('Field')->toArray();
            $debug['columns']['customer_measurements_has_is_seen'] = in_array('is_seen', collect($columns)->pluck('Field')->toArray());
        } catch (\Exception $e) {
            $debug['columns']['customer_measurements'] = ['error' => $e->getMessage()];
        }

        // Contact submissions columns
        try {
            $columns = DB::select("SHOW COLUMNS FROM contact_submissions");
            $debug['columns']['contact_submissions'] = collect($columns)->pluck('Field')->toArray();
            $debug['columns']['contact_submissions_has_is_read'] = in_array('is_read', collect($columns)->pluck('Field')->toArray());
        } catch (\Exception $e) {
            $debug['columns']['contact_submissions'] = ['error' => $e->getMessage()];
        }

        // 4. Raw data queries
        $debug['data'] = [];

        // Orders data
        try {
            $all_orders = Order::count();
            $unseen_orders = Order::where('is_seen', false)->count();
            $sample_order = Order::first();
            
            $debug['data']['orders'] = [
                'total' => $all_orders,
                'unseen' => $unseen_orders,
                'sample' => $sample_order ? $sample_order->toArray() : null,
                'error' => null,
            ];
        } catch (\Exception $e) {
            $debug['data']['orders'] = [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ];
        }

        // Customer measurements data
        try {
            $all_measurements = CustomerMeasurement::count();
            $unseen_measurements = CustomerMeasurement::where('is_seen', false)->count();
            $sample_measurement = CustomerMeasurement::first();
            
            $debug['data']['measurements'] = [
                'total' => $all_measurements,
                'unseen' => $unseen_measurements,
                'sample' => $sample_measurement ? $sample_measurement->toArray() : null,
                'error' => null,
            ];
        } catch (\Exception $e) {
            $debug['data']['measurements'] = [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ];
        }

        // Contact submissions data
        try {
            $all_contacts = ContactSubmission::count();
            $unread_contacts = ContactSubmission::where('is_read', false)->count();
            $messages = ContactSubmission::where('type', 'message')->count();
            $tailoring = ContactSubmission::where('type', 'tailoring_request')->count();
            $sample_contact = ContactSubmission::first();
            
            $debug['data']['contact_submissions'] = [
                'total' => $all_contacts,
                'unread' => $unread_contacts,
                'messages' => $messages,
                'tailoring_requests' => $tailoring,
                'sample' => $sample_contact ? $sample_contact->toArray() : null,
                'error' => null,
            ];
        } catch (\Exception $e) {
            $debug['data']['contact_submissions'] = [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ];
        }

        // 5. Users data
        try {
            $users_count = User::count();
            $sample_user = User::first();
            
            $debug['data']['users'] = [
                'total' => $users_count,
                'sample' => $sample_user ? $sample_user->toArray() : null,
                'error' => null,
            ];
        } catch (\Exception $e) {
            $debug['data']['users'] = [
                'error' => $e->getMessage(),
            ];
        }

        // 6. Scope tests
        $debug['scopes'] = [];

        try {
            $unseen = Order::unseen()->get();
            $debug['scopes']['order_unseen'] = [
                'works' => true,
                'count' => count($unseen),
                'error' => null,
            ];
        } catch (\Exception $e) {
            $debug['scopes']['order_unseen'] = [
                'works' => false,
                'error' => $e->getMessage(),
            ];
        }

        try {
            $unread = ContactSubmission::unread()->get();
            $debug['scopes']['contact_unread'] = [
                'works' => true,
                'count' => count($unread),
                'error' => null,
            ];
        } catch (\Exception $e) {
            $debug['scopes']['contact_unread'] = [
                'works' => false,
                'error' => $e->getMessage(),
            ];
        }

        try {
            $unseen_meas = CustomerMeasurement::unseen()->get();
            $debug['scopes']['measurement_unseen'] = [
                'works' => true,
                'count' => count($unseen_meas),
                'error' => null,
            ];
        } catch (\Exception $e) {
            $debug['scopes']['measurement_unseen'] = [
                'works' => false,
                'error' => $e->getMessage(),
            ];
        }

        // 7. Test the notification controller logic
        $debug['controller_test'] = [];

        try {
            $adminOrderShowRoute = route('admin.orders.show', ['id' => ':ID:']);
            $debug['controller_test']['route_admin_orders'] = $adminOrderShowRoute;
        } catch (\Exception $e) {
            $debug['controller_test']['route_admin_orders_error'] = $e->getMessage();
        }

        try {
            $adminContactShowRoute = route('admin.contact-submissions.show', ['contact_submission' => ':ID:']);
            $debug['controller_test']['route_admin_contact'] = $adminContactShowRoute;
        } catch (\Exception $e) {
            $debug['controller_test']['route_admin_contact_error'] = $e->getMessage();
        }

        try {
            $measurementRoute = route('receptionist.measurements.show', ['measurement' => ':ID:']);
            $debug['controller_test']['route_measurements'] = $measurementRoute;
        } catch (\Exception $e) {
            $debug['controller_test']['route_measurements_error'] = $e->getMessage();
        }

        // 8. Full notification simulation
        $debug['simulation'] = [];

        try {
            $allNotifications = [];
            
            // Simulate orders
            try {
                $unseenOrders = Order::with('user')->unseen()->latest('created_at')->limit(5)->get();
                $debug['simulation']['orders_query'] = [
                    'success' => true,
                    'count' => count($unseenOrders),
                    'data' => $unseenOrders->map(fn($o) => [
                        'id' => $o->id,
                        'order_number' => $o->order_number,
                        'is_seen' => $o->is_seen,
                        'user_id' => $o->user_id,
                        'user_name' => $o->user?->name,
                    ])->toArray(),
                ];
            } catch (\Exception $e) {
                $debug['simulation']['orders_query'] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }

            // Simulate messages
            try {
                $messages = ContactSubmission::where('type', 'message')->unread()->latest('created_at')->limit(5)->get();
                $debug['simulation']['messages_query'] = [
                    'success' => true,
                    'count' => count($messages),
                    'data' => $messages->map(fn($m) => [
                        'id' => $m->id,
                        'full_name' => $m->full_name,
                        'email' => $m->email,
                        'type' => $m->type,
                        'is_read' => $m->is_read,
                    ])->toArray(),
                ];
            } catch (\Exception $e) {
                $debug['simulation']['messages_query'] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }

            // Simulate measurements
            try {
                $measurements = CustomerMeasurement::with('user')->unseen()->latest('created_at')->limit(5)->get();
                $debug['simulation']['measurements_query'] = [
                    'success' => true,
                    'count' => count($measurements),
                    'data' => $measurements->map(fn($m) => [
                        'id' => $m->id,
                        'profile_name' => $m->profile_name,
                        'user_id' => $m->user_id,
                        'user_name' => $m->user?->name,
                        'is_seen' => $m->is_seen,
                    ])->toArray(),
                ];
            } catch (\Exception $e) {
                $debug['simulation']['measurements_query'] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }

        } catch (\Exception $e) {
            $debug['simulation']['error'] = $e->getMessage();
        }

        return response()->json($debug, 200);
    }

    /**
     * Simple endpoint - just show what's in the database
     */
    public function simple()
    {
        return response()->json([
            'orders' => Order::count(),
            'unseen_orders' => DB::table('orders')->where('is_seen', false)->count(),
            'measurements' => CustomerMeasurement::count(),
            'unseen_measurements' => DB::table('customer_measurements')->where('is_seen', false)->count(),
            'contacts' => ContactSubmission::count(),
            'unread_contacts' => DB::table('contact_submissions')->where('is_read', false)->count(),
        ]);
    }

    /**
     * Check specific table structure
     */
    public function tableStructure($table)
    {
        $allowed = ['orders', 'customer_measurements', 'contact_submissions', 'users'];
        if (!in_array($table, $allowed)) {
            return response()->json(['error' => 'Invalid table'], 400);
        }

        try {
            $columns = DB::select("SHOW COLUMNS FROM {$table}");
            $records = DB::table($table)->limit(5)->get();
            
            return response()->json([
                'table' => $table,
                'columns' => collect($columns)->map(fn($col) => [
                    'field' => $col->Field,
                    'type' => $col->Type,
                    'null' => $col->Null,
                    'key' => $col->Key,
                ])->toArray(),
                'sample_records' => $records->toArray(),
                'total_records' => DB::table($table)->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
