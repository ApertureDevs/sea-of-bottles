import {Component, EventEmitter, Output} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {MessageService} from '@data/service/message.service';
import {CreateSailorCommand} from '@model/domain/message/command/create-sailor-command';
import {AlertService} from '@core/alert/alert.service';

@Component({
  templateUrl: './create-sailor.component.html',
  styleUrls: ['./create-sailor.component.scss'],
})
export class CreateSailorComponent {
  public wasCreated = false;
  public form: FormGroup;
  @Output() public sailorCreated = new EventEmitter<void>();

  public constructor(
    private messageService: MessageService,
    private formBuilder: FormBuilder,
    private alertService: AlertService,
  ) {
    this.form = this.formBuilder.group({
      email: ['', [Validators.required, Validators.email]],
    });
  }

  public commandSubmit(): void
  {
    if (!this.form.valid) {
      return;
    }

    const command: CreateSailorCommand = {
      email: this.form.get('email')?.value,
    };

    this.messageService.createSailor(command).subscribe(() => {
      this.wasCreated = true;
      this.alertService.info('Sailor added successfully');
      this.sailorCreated.emit();
    });
  }
}
