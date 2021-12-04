import {Injectable} from '@angular/core';
import {Observable, of} from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class StorageService {
  private storage: Storage;

  public constructor() {
    this.storage = localStorage;
  }

  public getItem<T>(key: string): Observable<T|null> {
    const item = this.storage.getItem(key);

    if (item === null) {
      return of(null);
    }

    return of(JSON.parse(item));
  }

  public setItem<T>(key: string, value: T): Observable<void> {
    return of(this.storage.setItem(key, JSON.stringify(value)));
  }

  public removeItem(key: string): Observable<void> {
    return of(this.storage.removeItem(key));
  }
}
