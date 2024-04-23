import { ComponentFixture, TestBed } from '@angular/core/testing';

import { WsconnectComponent } from './wsconnect.component';

describe('WsconnectComponent', () => {
  let component: WsconnectComponent;
  let fixture: ComponentFixture<WsconnectComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [WsconnectComponent]
    });
    fixture = TestBed.createComponent(WsconnectComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
