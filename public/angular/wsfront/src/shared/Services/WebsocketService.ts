import {Observable, Subject} from "rxjs";
import {Injectable, Optional, SkipSelf} from "@angular/core";
//https://www.youtube.com/watch?v=V4iMyVnQPqM
//https://www.youtube.com/watch?v=DgsMSLHM-fY
console.log('loadd');

@Injectable({providedIn: 'any'})
export class WebsocketService {
  private subjectOnOpen = new Subject();
  private subjectOnClose = new Subject();
  private subjectOnError = new Subject();
  private subjectOnMessage = new Subject();
  private socket!: WebSocket;

  connect(url: string): void {

    this.socket = new WebSocket(url);

    this.socket.onopen = (event) => {
      this.subjectOnOpen.next(event);
    };

    this.socket.onmessage = (event) => {
      this.subjectOnMessage.next(event);
    };

    this.socket.onclose = (event) => {
      this.subjectOnClose.next(event);
    };

    this.socket.onerror = (event) => {
      this.subjectOnError.next(event);
    };
  }

  onopen(callback: (event: Event) => void) {
    this.subjectOnOpen.asObservable().subscribe((nextObj: any) => {
      callback(nextObj);
    });
  }

  onmessage(callback: (event: MessageEvent) => void) {
    this.subjectOnMessage.asObservable().subscribe((nextObj: any) => {
      callback(nextObj);
    });
  }

  onclose(callback: (event: CloseEvent) => void) {
    this.subjectOnClose.asObservable().subscribe((nextObj: any) => {
      callback(nextObj);
    });
  }

  onerror(callback: (event: Event) => void) {
    this.subjectOnError.asObservable().subscribe((nextObj: any) => {
      callback(nextObj);
    });
  }

  sendMessage(message: string): void {
    this.socket.send(message);
  }

  closeConnection(): void {
    if (this.socket) {
      this.socket.close();
    }
  }
}
