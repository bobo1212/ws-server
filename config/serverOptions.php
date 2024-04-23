<?php


//https://github.com/openswoole/ext-openswoole/blob/b8213c29149e1f4c40e7b4fb27aa78c0657f3484/ext-src/swoole_server_port.cc
/*
 *   // global
    { "debug_mode", true },
    { "trace_flags", true },
    { "log_file", true },
    { "log_level", true },
    { "log_date_format", true },
    { "log_date_with_microseconds", true },
    { "log_rotation", true },
    { "display_errors", true },
    { "dns_server", true },
    { "socket_dns_timeout", true },
    { "socket_connect_timeout", true },
    { "socket_write_timeout", true },
    { "socket_send_timeout", true },
    { "socket_read_timeout", true },
    { "socket_recv_timeout", true },
    { "socket_buffer_size", true },
    { "socket_timeout", true },

    // server
    { "chroot", true },
    { "user", true },
    { "group", true },
    { "daemonize", true },
    { "pid_file", true },
    { "reactor_num", true },
    { "single_thread", true },
    { "worker_num", true },
    { "max_wait_time", true },
    { "max_queued_bytes", true },
    { "enable_coroutine", true },
    { "max_coro_num", true },
    { "max_coroutine", true },
    { "hook_flags", true },
    { "send_timeout", true },
    { "dispatch_mode", true },
    { "send_yield", true },
    { "dispatch_func", true },
    { "discard_timeout_request", true },
    { "enable_unsafe_event", true },
    { "enable_delay_receive", true },
    { "enable_reuse_port", true },
    { "task_use_object", true },
    { "task_object", true },
    { "event_object", true },
    { "task_enable_coroutine", true },
    { "task_worker_num", true },
    { "task_ipc_mode", true },
    { "task_tmpdir", true },
    { "task_max_request", true },
    { "task_max_request_grace", true },
    { "max_connection", true },
    { "max_conn", true },
    { "start_session_id", true },
    { "heartbeat_check_interval", true },
    { "heartbeat_idle_time", true },
    { "max_request", true },
    { "max_request_grace", true },
    { "max_request_execution_time", true },
    { "reload_async", true },
    { "open_cpu_affinity", true },
    { "cpu_affinity_ignore", true },
    { "http_parse_cookie", true },
    { "http_parse_post", true },
    { "http_parse_files", true },
    { "http_compression", true },
    { "http_compression_level", true },
    { "compression_min_length", true },
    { "http_gzip_level", true },
    { "websocket_compression", true },
    { "upload_tmp_dir", true },
    { "enable_static_handler", true },
    { "document_root", true },
    { "http_autoindex", true },
    { "http_index_files", true },
    { "static_handler_locations", true },
    { "input_buffer_size", true },
    { "buffer_input_size", true },
    { "output_buffer_size", true },
    { "buffer_output_size", true },
    { "message_queue_key", true },
    { "http2_header_table_size", true },
    { "http2_initial_window_size", true },
    { "http2_max_concurrent_streams", true },
    { "http2_max_frame_size", true },
    { "http2_max_header_list_size", true },
    { "enable_server_token", true },

    // port
    { "ssl_cert_file", true },
    { "ssl_key_file", true },
    { "backlog", true },
    { "socket_buffer_size", true },
    { "kernel_socket_recv_buffer_size", true },
    { "kernel_socket_send_buffer_size", true },
    { "buffer_high_watermark", true },
    { "buffer_low_watermark", true },
    { "open_tcp_nodelay", true },
    { "tcp_defer_accept", true },
    { "open_tcp_keepalive", true },
    { "open_eof_check", true },
    { "open_eof_split", true },
    { "package_eof", true },
    { "open_http_protocol", true },
    { "open_websocket_protocol", true },
    { "websocket_subprotocol", true },
    { "open_websocket_close_frame", true },
    { "open_websocket_ping_frame", true },
    { "open_websocket_pong_frame", true },
    { "open_http2_protocol", true },
    { "open_mqtt_protocol", true },
    { "open_redis_protocol", true },
    { "max_idle_time", true },
    { "tcp_keepidle", true },
    { "tcp_keepinterval", true },
    { "tcp_keepcount", true },
    { "tcp_user_timeout", true },
    { "tcp_fastopen", true },
    { "open_length_check", true },
    { "package_length_type", true },
    { "package_length_offset", true },
    { "package_body_offset", true },
    { "package_body_start", true },
    { "package_length_func", true },
    { "package_max_length", true },
    { "ssl_compress", true },
    { "ssl_protocols", true },
    { "ssl_verify_peer", true },
    { "ssl_allow_self_signed", true },
    { "ssl_client_cert_file", true },
    { "ssl_verify_depth", true },
    { "ssl_prefer_server_ciphers", true },
    { "ssl_ciphers", true },
    { "ssl_ecdh_curve", true },
    { "ssl_dhparam", true },
    { "ssl_sni_certs", true }
 * */
$max_request = 100;
return [

    "max_queued_bytes" => 1048576, //wielkość kolejki wejściowej po rzekroczeniu zacznie zwalniać zczytyanie danych od producenta
    //     "buffer_input_size" => 1048576,
//      "output_buffer_size" => 1048576,
//      "buffer_output_size" => 1048576,
//      "http2_header_table_size" => 1048576,
//      "http2_initial_window_size" => 1048576,
//      "http2_max_frame_size" => 1048576,
//      "http2_max_header_list_size" => 1048576,
//      "kernel_socket_recv_buffer_size" => 1048576,
//      "kernel_socket_send_buffer_size" => 1048576,
//    //'pipe_buffer_size' => 1048576,


// Process
//    'daemonize' => 1,
//    'user' => 'www-data',
//    'group' => 'www-data',
//    'chroot' => '/data/server/',
//    'open_cpu_affinity' => true,
//    'cpu_affinity_ignore' => [0, 1],
//    'pid_file' => __DIR__ . '/server.pid',

// Server
//    'reactor_num' => 8,
    'worker_num' => 2,
//    'message_queue_key' => 'mq1',
    'dispatch_mode' => 2, //2 Fixed Mode: The Default mode. Dispatches the connection to the worker according to the ID number of connection (file descriptor). In this mode, the data from the same connection will be handled by the same worker process.
//    'discard_timeout_request' => true,
//    'dispatch_func' => 'my_dispatch_function',

// Worker
//    'max_request' => $max_request,
//    'max_request_grace' => $max_request / 2,

// HTTP Server max execution time, since v4.8.0
//    'max_request_execution_time' => 30, // 30s

// Task worker
//    'task_ipc_mode' => 1,
//    'task_max_request' => 100,
//    'task_tmpdir' => '/tmp',
//    'task_worker_num' => 8,
    'task_enable_coroutine' => false,
//    'task_use_object' => true,

// Logging
//    'log_level' => 1,
//    'log_file' => '/data/openswoole.log',
//    'log_rotation' => OpenSwoole\Constant::LOG_ROTATION_DAILY,
//    'log_date_format' => '%Y-%m-%d %H:%M:%S',
//    'log_date_with_microseconds' => false,
//    'request_slowlog_file' => false,

// Enable trace logs
//    'trace_flags' => OpenSwoole\Constant::TRACE_ALL,

// TCP
//    'input_buffer_size' =>1024,// 1 * 1024 * 1024,
//    'buffer_output_size' => 32 * 1024 * 1024, // byte in unit
//    'tcp_fastopen' => false,
    'max_conn' => 1024,
//    'tcp_defer_accept' => 5,
//    'open_tcp_keepalive' => true,
//    'open_tcp_nodelay' => false,
    //  'pipe_buffer_size' => 32 * 1024 * 1024,
    'socket_buffer_size' => 1 * 1024 * 1024, // ten bufor się przepełnia jak consumer za wolno odbiera. Po rzepełnieniu?

// Kernel
//    'backlog' => 512,
//    'kernel_socket_send_buffer_size' => 65535,
//    'kernel_socket_recv_buffer_size' => 65535,

// TCP Parser
//    'open_eof_check' => true,
//    'open_eof_split' => true,
//    'package_eof' => '\r\n',
   // 'open_length_check' => true,
   // 'package_length_type' => 'N',
//    'package_body_offset' => 8,
//    'package_length_offset' => 8,
  //  'package_max_length' => 10240 * 64,// 2 * 1024 * 1024, // 2MB
 //   'package_length_func' => 'myF',

// Coroutine
    'enable_coroutine' => false,
    //   'max_coroutine' => 3000,
//    'send_yield' => false,

// tcp server
//    'heartbeat_idle_time' => 600,
//    'heartbeat_check_interval' => 60,
//    'enable_delay_receive' => true,
//    'enable_reuse_port' => true,
//    'enable_unsafe_event' => true,

// Protocol
//    'open_http_protocol' => true,
//    'open_http2_protocol' => true,
//    'open_websocket_protocol' => true,
//    'open_mqtt_protocol' => true,

// HTTP2
//    'http2_header_table_size' => 4095,
//    'http2_initial_window_size' => 65534,
//    'http2_max_concurrent_streams' => 1281,
//    'http2_max_frame_size' => 16383,
//    'http2_max_header_list_size' => 4095,

// SSL
//    'ssl_cert_file' => __DIR__ . '/config/ssl.cert',
//    'ssl_key_file' => __DIR__ . '/config/ssl.key',
//    'ssl_ciphers' => 'ALL:!ADH:!EXPORT56:RC4+RSA:+HIGH:+MEDIUM:+LOW:+SSLv2:+EXP',
//    'ssl_method' => OpenSwoole\Constant::SSLv3_CLIENT_METHOD, // removed from v4.5.4
//    'ssl_protocols' => 0, // added from v4.5.4
//    'ssl_verify_peer' => false,
//    'ssl_sni_certs' => [
//        "cs.php.net" => [
//            'ssl_cert_file' => __DIR__ . "/config/sni_server_cs_cert.pem",
//            'ssl_key_file' => __DIR__ . "/config/sni_server_cs_key.pem"
//        ],
//        "uk.php.net" => [
//            'ssl_cert_file' => __DIR__ . "/config/sni_server_uk_cert.pem",
//            'ssl_key_file' => __DIR__ . "/config/sni_server_uk_key.pem"
//        ],
//        "us.php.net" => [
//            'ssl_cert_file' => __DIR__ . "/config/sni_server_us_cert.pem",
//            'ssl_key_file' => __DIR__ . "/config/sni_server_us_key.pem",
//        ],
//    ],

// Static Files
//    'document_root' => __DIR__ . '/public',
//    'enable_static_handler' => true,
//    'static_handler_locations' => ['/static', '/app/images'],
//    'http_index_files' => ['index.html', 'index.txt'],

// Source File Reloading
//    'reload_async' => true,
//    'max_wait_time' => 30,

// HTTP Server
//    'http_parse_post' => true,
//    'http_parse_cookie' => true,
//    'upload_tmp_dir' => '/tmp',

// Compression
//    'http_compression' => true,
//    'http_compression_level' => 3, // 1 - 9
//    'compression_min_length' => 20,


// Websocket
//    'websocket_compression' => true,
//    'open_websocket_close_frame' => false,
//    'open_websocket_ping_frame' => false, // added from v4.5.4
//    'open_websocket_pong_frame' => false, // added from v4.5.4

// TCP User Timeout
//    'tcp_user_timeout' => 0,

// DNS Server
//    'dns_server' => '8.8.8.8:53',
//    'dns_cache_refresh_time' => 60,
//    'enable_preemptive_scheduler' => 0,
//
//    'open_fastcgi_protocol' => 0,
//    'open_redis_protocol' => 0,
//
//    'stats_file' => './stats_file.txt', // removed from v4.9.0
//
//    'enable_object' => true,
];