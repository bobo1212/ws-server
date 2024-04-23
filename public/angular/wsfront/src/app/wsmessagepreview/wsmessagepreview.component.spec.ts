import { ComponentFixture, TestBed } from '@angular/core/testing';

import { WsmessagepreviewComponent } from './wsmessagepreview.component';

describe('WsmessagepreviewComponent', () => {
  let component: WsmessagepreviewComponent;
  let fixture: ComponentFixture<WsmessagepreviewComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [WsmessagepreviewComponent]
    });
    fixture = TestBed.createComponent(WsmessagepreviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
