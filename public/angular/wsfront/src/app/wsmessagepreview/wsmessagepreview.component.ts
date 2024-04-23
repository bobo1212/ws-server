import {Component, OnInit} from '@angular/core';
import {WebsocketService} from "../../shared/Services/WebsocketService";

@Component({
  selector: 'app-wsmessagepreview',
  templateUrl: './wsmessagepreview.component.html',
  styleUrls: ['./wsmessagepreview.component.css']
})
export class WsmessagepreviewComponent implements OnInit {
  messagePreview: string = ''
  constructor(private ws : WebsocketService){}
  ngOnInit(): void {
    this.ws.onmessage((event: MessageEvent) => {
      this.messagePreview = event.data;
    })
  }
}
