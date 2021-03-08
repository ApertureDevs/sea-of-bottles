import {Injectable} from '@angular/core';
import {MatSnackBar, MatSnackBarConfig} from '@angular/material/snack-bar';

@Injectable({
  providedIn: 'root',
})
export class AlertService {
  public constructor(
    private snackBar: MatSnackBar,
  ) { }

  public info(message: string): void {
    const config = new MatSnackBarConfig();
    config.duration = 5000;
    config.panelClass = ['alert', 'alert--info'];
    this.snackBar.open(message, '', config);
  }

  public error(message: string): void {
    const config = new MatSnackBarConfig();
    config.duration = 5000;
    config.panelClass = ['alert', 'alert--error'];
    this.snackBar.open(message, '', config);
  }
}
