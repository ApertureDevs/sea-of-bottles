import {Component, EventEmitter, Output} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {MessageService} from '@data/service/message.service';
import {MatSnackBar} from '@angular/material/snack-bar';
import {CreateSailorCommand} from '@model/domain/message/command/create-sailor-command';

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

    const command: CreateSailorCommand = {
      email: this.form.get('email')?.value,
    };

    this.messageService.createSailor(command).subscribe(() => {
      this.wasCreated = true;
      this.snackBar.open('Sailor added successfully', '',{duration: 5000});
      this.sailorCreated.emit();
    });
  }
}
