import {Injectable} from '@angular/core';
import {Observable, throwError} from 'rxjs';
import {Sea} from '@model/domain/message/projection/sea';
import {HttpClient, HttpErrorResponse, HttpHeaders} from '@angular/common/http';
import {CreateBottleCommand} from '@model/domain/message/command/create-bottle-command';
import {CreateSailorCommand} from '@model/domain/message/command/create-sailor-command';
import {DeleteSailorCommand} from '@model/domain/message/command/delete-sailor-command';
import {CommandResponse} from '@model/shared/api-response';
import {catchError} from 'rxjs/operators';
import {environment} from '../../../environments/environment';

@Injectable({
  providedIn: 'root',
})
export class MessageService {
  public constructor(
    private httpClient: HttpClient,
  ) {
  }

  public getSea(): Observable<Sea> {
    return this.httpClient.get<Sea>(`${environment.api.url}/api/sea`)
      .pipe(
        catchError(this.handleError),
      );
  }

  public createBottle(command: CreateBottleCommand): Observable<CommandResponse> {
    return this.httpClient.post<CommandResponse>(`${environment.api.url}/api/bottle`, command)
      .pipe(
        catchError(this.handleError),
      );
  }

  public createSailor(command: CreateSailorCommand): Observable<CommandResponse> {
    return this.httpClient.post<CommandResponse>(`${environment.api.url}/api/sailor`, command)
      .pipe(
        catchError(this.handleError),
      );
  }

  public deleteSailor(command: DeleteSailorCommand): Observable<CommandResponse> {
    const options = {
      headers: new HttpHeaders({
        'Content-Type': 'application/json',
      }),
      body: command,
    };

    return this.httpClient.delete<CommandResponse>(`${environment.api.url}/api/sailor`, options)
      .pipe(
        catchError(this.handleError),
      );
  }

  private handleError(error: HttpErrorResponse): Observable<never> {
    console.error('Invalid API response', error.error);

    return throwError(error.error);
  }
}
