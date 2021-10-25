import {Component, EventEmitter, Output} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {MessageService} from '@data/service/message.service';
import {DeleteSailorCommand} from '@model/domain/message/command/delete-sailor-command';
import {AlertService} from '@core/alert/alert.service';
import {TranslationService} from '@core/translation/translation.service';

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
    private translationService: TranslationService,
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

    this.messageService.deleteSailor(command).subscribe(async () => {
      this.wasDeleted = true;
      const translatedMessage = await this.translationService.translateKey('alert.sailor-deleted').toPromise();
      this.alertService.info(translatedMessage);
      this.sailorDeleted.emit();
    });
  }
}
