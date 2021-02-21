import {Component, OnInit} from '@angular/core';
import {MessageService} from '@data/service/message.service';
import {Sea} from '@model/domain/message/projection/sea';

@Component({
  templateUrl: './landing.component.html',
  styleUrls: ['./landing.component.scss'],
})
export class LandingComponent implements OnInit {
  public sea?: Sea;

  public constructor(private messageService: MessageService) {
  }

  public ngOnInit(): void {
    this.messageService.getSea().subscribe((sea) => {
      this.sea = sea;
    });
  }
}
