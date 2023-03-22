$(document).ready(function () {
    const wsUri = "ws://localhost:9032/admin";
    let socket = 0;

    function wcConnectInterval() {
        if (socket === 0 || socket.readyState === 3) {
            socket = new WebSocket(wsUri);
            socket.onopen = function (e) {
                $(".js-ws-on-open").removeClass('bg-secondary');
                $(".js-ws-on-open").removeClass('bg-danger');
                $(".js-ws-on-open").addClass('bg-success');
                setInterval(displayQuery, 1000);
            };

            socket.onmessage = function (event) {
                msg = JSON.parse(event.data);
                if (msg.msg === 'server') {
                    displayServerInfo(msg);
                    return;
                }
                if (msg.msg === 'app') {
                    displayAppInfo(msg);
                }
            };

            socket.onclose = function (event) {
                $(".js-ws-on-open").removeClass('bg-success');
                $(".js-ws-on-open").removeClass('bg-danger');
                $(".js-ws-on-open").addClass('bg-secondary');
            };

            socket.onerror = function (error) {
                $(".js-ws-on-open").removeClass('bg-success');
                $(".js-ws-on-open").removeClass('bg-secondary');
                $(".js-ws-on-open").addClass('bg-danger');
            };
        }
    }

    wcConnectInterval();
    setInterval(wcConnectInterval, 5000);

    function displayQuery() {
        socket.send('{"msg":"server"}');
        socket.send('{"msg":"app"}');
    }

    function getListHtml(listData) {
        let list = '';
        $.each(listData, function (key, value) {
            if (typeof value === 'object' || Array.isArray(value)) {
                value = value.length;
            }
            list += '<span style="margin:2px;background-color:rgba(0, 0, 0, 0.03); padding-left:5px; padding-right:5px; float: left; min-width: 19%"  class="d-flex justify-content-between">' + key + '  <span class="">' + value + '</span></span>\n';
        });
        list += '';
        return list;
    }

    function getTableHtml(tableData) {

        let html = '<thead>' +
            '<tr>';
        $.each(tableData, function (key, row) {

            $.each(row, function (k, v) {
                html += '<th>' + k + '</th>';
            });
            return false;
        });

        html += +'</tr>' +
            '</thead>' +
            '<tbody>';


        $.each(tableData, function (key, row) {
            html += '<tr>';
            $.each(row, function (k, v) {
                html += '<td>' + v + '</td>';
            });
            html += '</tr>';
        });
        html += '</tbody>';
        return html;
    }

    function displayServerInfo(msg) {
        $(".js-server-info").html(getListHtml(msg.stats));
        $("#js-event-workers-info").html(getTableHtml(msg.stats.event_workers));
    }
    function displayAppInfo(msg) {
        $("#js-app-info").html(getTableHtml(msg.app));
    }

    $(".js-reload-server").click(function (){
        socket.send('{"msg":"reload"}');
    });


});


/*
*
* "msg":"server",
   "stats":{
      "up":1,
      "version":"OpenSwoole-22.0.0",
      "master_pid":2449585,
      "manager_pid":2449586,
      "worker_id":3,
      "reactor_threads_num":8,
      "workers_total":12,
      "workers_idle":11,
      "task_workers_total":0,
      "task_workers_idle":0,
      "tasking_num":0,
      "user_workers_total":0,
      "dispatch_total":3,
      "requests_total":2,
      "start_time":1677147698,
      "start_seconds":52,
      "max_conn":100000,
      "connections_accepted":2,
      "connections_active":2,
      "connections_closed":0,
      "reload_count":0,
      "reload_last_time":1677147698,
      "worker_memory_usage":2097152,
      "worker_vm_object_num":24,
      "worker_vm_resource_num":3,
      "coroutine_num":1,
      "event_workers":[
         {
            "worker_id":0,
            "pid":2449595,
            "start_time":1677147698,
            "start_seconds":52,
            "request_count":0,
            "dispatch_count":0
         },
         {
            "worker_id":1,
            "pid":2449596,
            "start_time":1677147698,
            "start_seconds":52,
            "request_count":0,
            "dispatch_count":0
         },
         {
            "worker_id":2,
            "pid":2449597,
            "start_time":1677147698,
            "start_seconds":52,
            "request_count":1,
            "dispatch_count":1
         },
         {
            "worker_id":3,
            "pid":2449598,
            "start_time":1677147698,
            "start_seconds":52,
            "request_count":1,
            "dispatch_count":2
         },
         {
            "worker_id":4,
            "pid":2449599,
            "start_time":1677147698,
            "start_seconds":52,
            "request_count":0,
            "dispatch_count":0
         },
         {
            "worker_id":5,
            "pid":2449600,
            "start_time":1677147698,
            "start_seconds":52,
            "request_count":0,
            "dispatch_count":0
         },
         {
            "worker_id":6,
            "pid":2449601,
            "start_time":1677147698,
            "start_seconds":52,
            "request_count":0,
            "dispatch_count":0
         },
         {
            "worker_id":7,
            "pid":2449602,
            "start_time":1677147698,
            "start_seconds":52,
            "request_count":0,
            "dispatch_count":0
         },
         {
            "worker_id":8,
            "pid":2449603,
            "start_time":1677147698,
            "start_seconds":52,
            "request_count":0,
            "dispatch_count":0
         },
         {
            "worker_id":9,
            "pid":2449604,
            "start_time":1677147698,
            "start_seconds":52,
            "request_count":0,
            "dispatch_count":0
         },
         {
            "worker_id":10,
            "pid":2449605,
            "start_time":1677147698,
            "start_seconds":52,
            "request_count":0,
            "dispatch_count":0
         },
         {
            "worker_id":11,
            "pid":2449606,
            "start_time":1677147698,
            "start_seconds":52,
            "request_count":0,
            "dispatch_count":0
         }
      ],
      "task_workers":[

      ],
      "user_workers":[

      ],
      "top_classes":{
         "Closure":10,
         "OpenSwoole\\Table":3,
         "OpenSwoole\\Connection\\Iterator":2,
         "OpenSwoole\\WebSocket\\Frame":1,
         "Bobo121278\\WsServerOpenSwoole\\app\\AppClientServer":1,
         "Bobo121278\\WsServerOpenSwoole\\Repo\\Users":1,
         "Bobo121278\\WsServerOpenSwoole\\Repo\\Memory":1,
         "Bobo121278\\WsServerOpenSwoole\\Repo\\Uri":1,
         "Bobo121278\\WsServerOpenSwoole\\app\\AppAdmin":1,
         "Bobo121278\\WsServerOpenSwoole\\app\\AppToAll":1,
         "OpenSwoole\\Server\\Port":1,
         "OpenSwoole\\WebSocket\\Server":1
      }
   }
}
*
* */
