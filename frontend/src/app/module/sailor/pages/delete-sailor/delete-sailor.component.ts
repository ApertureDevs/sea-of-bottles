import {Component, EventEmitter, Output} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {MessageService} from '@data/service/message.service';
import {DeleteSailorCommand} from '@model/domain/message/command/delete-sailor-command';
import {AlertService} from '@core/alert/alert.service';

@Component({
  templateUrl: './delete-sailor.component.html',
  styleUrls: ['./delete-sailor.component.scss'],
})
export class DeleteSailorComponent {
  public wasDeleted = false;
  public form: FormGroup;
  @Output() public sailorDeleted = new EventEmitter<void>();

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

    const command: DeleteSailorCommand = {
      email: this.form.get('email')?.value,
    };

    this.messageService.deleteSailor(command).subscribe(() => {
      this.wasDeleted = true;
      this.alertService.info('Sailor removed successfully');
      this.sailorDeleted.emit();
    });
  }
}
