import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { WsconnectComponent } from './wsconnect/wsconnect.component';
import {FormsModule} from "@angular/forms";
import { WsmessagepreviewComponent } from './wsmessagepreview/wsmessagepreview.component';
import {WebsocketService} from "../shared/Services/WebsocketService";
console.log('LOAD MODULE APP');
@NgModule({
  declarations: [ // kompnenty albo dyrektywy zdeklarowane w tym module
    AppComponent,
    WsconnectComponent,
    WsmessagepreviewComponent
  ],
  imports: [ // elementy dostępne dla szblonów w tym module
    BrowserModule,
    AppRoutingModule,
    FormsModule
  ],
  providers: [], // obiekty które mogą być wstrzykiwane
  bootstrap: [AppComponent] // co zostanie zbutstrapowane
})
export class AppModule {

  constructor() {
    console.log('CONSTRUCT MODULE APP');
  }
}
