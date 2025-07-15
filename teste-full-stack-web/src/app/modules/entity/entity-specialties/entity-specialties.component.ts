import { CommonModule } from '@angular/common';
import { Component, ViewEncapsulation, Inject } from '@angular/core';
import { MatButtonModule } from '@angular/material/button';
import {
    MAT_DIALOG_DATA,
    MatDialogModule,
    MatDialogRef,
} from '@angular/material/dialog';
import { MatIconModule } from '@angular/material/icon';
import { Specialty } from 'app/_models/specialty.model';

export interface DialogData {
    allSpecialties: Specialty;
}
@Component({
    selector: 'app-specialties',
    templateUrl: './entity-specialties.component.html',
    standalone: true,
    encapsulation: ViewEncapsulation.None,
    imports: [MatButtonModule, CommonModule, MatDialogModule, MatIconModule],
})
export class EntitySpecialtiesComponent {
    constructor(
        public dialogRef: MatDialogRef<EntitySpecialtiesComponent>,
        @Inject(MAT_DIALOG_DATA) public data: DialogData
    ) {}
}
