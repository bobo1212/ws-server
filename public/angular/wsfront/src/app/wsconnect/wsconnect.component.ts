//https://www.youtube.com/watch?v=JWhRMyyF7nc&t=6511s
//https://indepth.dev/tutorials/angular/how-to-implement-websockets-in-angular-project
//https://www.c-sharpcorner.com/article/real-time-communication-made-easy-demonstrating-web-sockets-with-angular/
//https://stackoverflow.com/questions/49699067/property-has-no-initializer-and-is-not-definitely-assigned-in-the-construc
//https://angular.io/errors/NG0201
//https://stackoverflow.com/questions/35763730/difference-between-constructor-and-ngoninit
import {Component, OnInit} from '@angular/core';
import {WebsocketService} from "../../shared/Services/WebsocketService";

@Component({
  selector: 'app-wsconnect',
  templateUrl: './wsconnect.component.html',
  styleUrls: ['./wsconnect.component.css']
})
export class WsconnectComponent implements OnInit {

  wsUri: string = 'ws://localhost:9032';
  connectionStatus: string = 'close';
  wsErrorMsg: string = '';
  wsMessage: string = '';

  constructor(private ws: WebsocketService) {
  }

  ngOnInit(): void {
    this.ws.onopen((event) => {
      this.connectionStatus = 'open';
    });
    this.ws.onclose((event) => {
      this.connectionStatus = 'close';
    });
    this.ws.onerror((event) => {
      this.connectionStatus = 'error';
      this.wsErrorMsg = 'Websocket error';
    });
  }

  onClickConnectToWs() {
    this.wsErrorMsg = '';
    try {
      this.ws.connect(this.wsUri);
    } catch (e: any) {
      this.wsErrorMsg = e.message;
    }
  };

  onClickCloseWs() {
    this.ws.closeConnection();
  }
}
