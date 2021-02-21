import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';
import {Sea} from '@model/domain/message/projection/sea';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {CreateBottleCommand} from '@model/domain/message/command/create-bottle-command';
import {CreateSailorCommand} from '@model/domain/message/command/create-sailor-command';
import {DeleteSailorCommand} from '@model/domain/message/command/delete-sailor-command';
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
    return this.httpClient.get<Sea>(`${environment.api.url}/api/sea`);
  }

  public createBottle(command: CreateBottleCommand): Observable<string> {
    return this.httpClient.post<string>(`${environment.api.url}/api/bottle`, command);
  }

  public createSailor(command: CreateSailorCommand): Observable<string> {
    return this.httpClient.post<string>(`${environment.api.url}/api/sailor`, command);
  }

  public deleteSailor(command: DeleteSailorCommand): Observable<string> {
    const options = {
      headers: new HttpHeaders({
        'Content-Type': 'application/json',
      }),
      body: command,
    };

    return this.httpClient.delete<string>(`${environment.api.url}/api/sailor`, options);
  }
}
