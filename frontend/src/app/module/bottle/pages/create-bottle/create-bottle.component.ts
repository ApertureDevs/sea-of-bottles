import {Component} from '@angular/core';
import {MessageService} from '@data/service/message.service';
import {CreateBottleCommand} from '@model/domain/message/command/create-bottle-command';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {MatSnackBar} from '@angular/material/snack-bar';

@Component({
  templateUrl: './create-bottle.component.html',
  styleUrls: ['./create-bottle.component.scss'],
})
export class CreateBottleComponent {
  public wasSubmitted = false;
  public form: FormGroup;

  public constructor(
    private messageService: MessageService,
    private formBuilder: FormBuilder,
    private snackBar: MatSnackBar,
  ) {
    this.form = this.formBuilder.group({
      message: ['', [Validators.required, Validators.minLength(5), Validators.maxLength(500)]],
    });
  }

  public commandSubmit(): void
  {
    const command: CreateBottleCommand = {
      message: this.form.get('message')?.value,
    };
    this.messageService.createBottle(command).subscribe(() => {
      this.wasSubmitted = true;
      this.snackBar.open('Bottle sent successfully', '',{duration: 5000});
    });
  }

  public reset(): void
  {
    this.wasSubmitted = false;
    this.form = this.formBuilder.group({
      message: ['', [Validators.required, Validators.minLength(5), Validators.maxLength(500)]],
    });
  }
}
