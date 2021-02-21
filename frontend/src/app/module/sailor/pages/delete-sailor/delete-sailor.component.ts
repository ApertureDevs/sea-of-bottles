import {Component} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {MessageService} from '@data/service/message.service';
import {MatSnackBar} from '@angular/material/snack-bar';
import {DeleteSailorCommand} from '@model/domain/message/command/delete-sailor-command';

@Component({
  templateUrl: './delete-sailor.component.html',
  styleUrls: ['./delete-sailor.component.scss'],
})
export class DeleteSailorComponent {
  public wasSubmitted = false;
  public form: FormGroup;

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
    const command: DeleteSailorCommand = {
      email: this.form.get('email')?.value,
    };
    this.messageService.deleteSailor(command).subscribe(() => {
      this.wasSubmitted = true;
      this.snackBar.open('Sailor removed successfully', '',{duration: 5000});
    });
  }
}
