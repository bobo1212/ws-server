import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {WsconnectComponent} from './wsconnect/wsconnect.component'
import {WsmessagepreviewComponent} from "./wsmessagepreview/wsmessagepreview.component";

const routes: Routes = [
  { path :'connect', component: WsconnectComponent},
  { path :'msg', component: WsmessagepreviewComponent},
];
console.log('LOAD ROUTING');
@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})

export class AppRoutingModule {
  constructor() {
    console.log(' ROUTING CONSTRUCTOR ');
  }
}
