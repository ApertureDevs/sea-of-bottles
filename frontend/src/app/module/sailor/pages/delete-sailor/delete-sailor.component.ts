import {Component, EventEmitter, Output} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {MessageService} from '@data/service/message.service';
import {MatSnackBar} from '@angular/material/snack-bar';
import {DeleteSailorCommand} from '@model/domain/message/command/delete-sailor-command';

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
    private snackBar: MatSnackBar,
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
      this.snackBar.open('Sailor removed successfully', '',{duration: 5000});
      this.sailorDeleted.emit();
    });
  }
}
