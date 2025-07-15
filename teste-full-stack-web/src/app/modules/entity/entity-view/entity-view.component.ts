import { Component, ViewEncapsulation, Inject } from '@angular/core';
import { MatButtonModule } from '@angular/material/button';
import { Entity } from 'app/_models/entity.model';
import { EntityService } from 'app/_services/entity.services';
import { ActivatedRoute } from '@angular/router';
import { CommonModule } from '@angular/common';
import { StatusComponent } from 'app/shared/componentes/status/status.component';
import { EntitySpecialtiesComponent } from '../entity-specialties/entity-specialties.component';
import {
    MAT_DIALOG_DATA,
    MatDialog,
    MatDialogModule,
    MatDialogRef,
} from '@angular/material/dialog';
import { EntityStoreComponent } from '../entity-store/entity-store.component';
import { Specialty } from 'app/_models/specialty.model';

export interface DialogData {
    entity: Entity;
}
@Component({
    selector: 'entity-view',
    standalone: true,
    templateUrl: './entity-view.component.html',
    encapsulation: ViewEncapsulation.None,
    imports: [MatButtonModule, CommonModule, StatusComponent, MatDialogModule],
})
export class EntityViewComponent {
    specialtiesToShow = [];
    showMoreButton = false;
    remainingSpecialtiesCount = 0;
    allSpecialties: Specialty[] = [];

    constructor(
        public dialogRef: MatDialogRef<EntityViewComponent>,
        @Inject(MAT_DIALOG_DATA) public data: DialogData,
        public dialog: MatDialog
    ) {}

    ngOnInit() {
        this.allSpecialties = this.data?.entity?.specialties || [];

        if (this.allSpecialties.length >= 5) {
            this.specialtiesToShow = this.allSpecialties.slice(0, 2);
            this.showMoreButton = true;
            this.remainingSpecialtiesCount = this.allSpecialties.length - 2;
        } else {
            this.specialtiesToShow = this.allSpecialties;
            this.showMoreButton = false;
        }
    }

    update(entity: Entity): void {
        const dialogRef = this.dialog.open(EntityStoreComponent, {
            panelClass: 'fuse-confirmation-dialog-panel',
            data: { entity: entity },
            width: '900px',
        });
    }

    openSpecialtiesModal(): void {
        const remainingSpecialties = this.allSpecialties.filter(
            (item) =>
                !this.specialtiesToShow.some(
                    (showItem) => showItem.id === item.id
                )
        );

        this.dialog.open(EntitySpecialtiesComponent, {
            panelClass: 'fuse-confirmation-dialog-panel',
            data: { allSpecialties: remainingSpecialties },
            width: '400px',
        });
    }
}
