import {Component, EventEmitter, Output} from '@angular/core';
import {MessageService} from '@data/service/message.service';
import {CreateBottleCommand} from '@model/domain/message/command/create-bottle-command';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {AlertService} from '@core/alert/alert.service';

@Component({
  templateUrl: './create-bottle.component.html',
  styleUrls: ['./create-bottle.component.scss'],
})
export class CreateBottleComponent {
  public wasCreated = false;
  public form: FormGroup;
  @Output() public bottleCreated = new EventEmitter<void>();

  public constructor(
    private messageService: MessageService,
    private formBuilder: FormBuilder,
    private alertService: AlertService,
  ) {
    this.form = this.formBuilder.group({
      message: ['', [Validators.required, Validators.minLength(5), Validators.maxLength(500)]],
    });
  }

  public commandSubmit(): void
  {
    if (!this.form.valid) {
      return;
    }

    const command: CreateBottleCommand = {
      message: this.form.get('message')?.value,
    };

    this.messageService.createBottle(command).subscribe(() => {
      this.wasCreated = true;
      this.alertService.info('Bottle sent successfully');
      this.bottleCreated.emit();
    });
  }

  public reset(): void
  {
    this.wasCreated = false;
    this.form = this.formBuilder.group({
      message: ['', [Validators.required, Validators.minLength(5), Validators.maxLength(500)]],
    });
  }
}
