<?php
$max_request = 100;
return [
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
//    'dispatch_mode' => 2,
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
    'task_enable_coroutine' => true,
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
//    'input_buffer_size' => 2097152,
//    'buffer_output_size' => 32 * 1024 * 1024, // byte in unit
//    'tcp_fastopen' => false,
    'max_conn' => 1000,
//    'tcp_defer_accept' => 5,
//    'open_tcp_keepalive' => true,
//    'open_tcp_nodelay' => false,
//    'pipe_buffer_size' => 32 * 1024 * 1024,
//    'socket_buffer_size' => 128 * 1024 * 1024,

// Kernel
//    'backlog' => 512,
//    'kernel_socket_send_buffer_size' => 65535,
//    'kernel_socket_recv_buffer_size' => 65535,

// TCP Parser
//    'open_eof_check' => true,
//    'open_eof_split' => true,
//    'package_eof' => '\r\n',
//    'open_length_check' => true,
//    'package_length_type' => 'N',
//    'package_body_offset' => 8,
//    'package_length_offset' => 8,
//    'package_max_length' => 2 * 1024 * 1024, // 2MB
//    'package_length_func' => 'my_package_length_func',

// Coroutine
//    'enable_coroutine' => true,
    'max_coroutine' => 3000,
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