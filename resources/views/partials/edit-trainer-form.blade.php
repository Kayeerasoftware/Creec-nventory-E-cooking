<!-- Edit Trainer Modal -->
<div class="modal fade" id="editTrainerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Edit My Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editTrainerForm">
                    @csrf
                    <input type="hidden" id="editTrainerId">
                    <div class="row g-3">
                        <div class="col-md-6"><label>Phone</label><input type="tel" class="form-control" name="phone" id="editTrainerPhone"></div>
                        <div class="col-md-6"><label>WhatsApp</label><input type="tel" class="form-control" name="whatsapp" id="editTrainerWhatsapp"></div>
                        <div class="col-md-6"><label>Emergency Contact</label><input type="text" class="form-control" name="emergency_contact" id="editTrainerEmergency"></div>
                        <div class="col-md-6"><label>Village</label><input type="text" class="form-control" name="village" id="editTrainerVillage"></div>
                        <div class="col-md-6"><label>Postal Code</label><input type="text" class="form-control" name="postal_code" id="editTrainerPostal"></div>
                        <div class="col-12"><label>Skills</label><textarea class="form-control" name="skills" id="editTrainerSkills" rows="2"></textarea></div>
                        <div class="col-12"><label>Qualifications</label><textarea class="form-control" name="qualifications" id="editTrainerQuals" rows="2"></textarea></div>
                        <div class="col-12"><label>Certifications</label><textarea class="form-control" name="certifications" id="editTrainerCerts" rows="2"></textarea></div>
                        <div class="col-12"><label>Languages</label><textarea class="form-control" name="languages" id="editTrainerLangs" rows="2"></textarea></div>
                        <div class="col-12"><label>Notes</label><textarea class="form-control" name="notes" id="editTrainerNotes" rows="2"></textarea></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveTrainerEdit()">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
function openEditTrainer(id) {
    fetch(`/api/trainers/${id}`).then(r => r.json()).then(t => {
        document.getElementById('editTrainerId').value = t.id;
        document.getElementById('editTrainerPhone').value = t.phone || '';
        document.getElementById('editTrainerWhatsapp').value = t.whatsapp || '';
        document.getElementById('editTrainerEmergency').value = t.emergency_contact || '';
        document.getElementById('editTrainerVillage').value = t.village || '';
        document.getElementById('editTrainerPostal').value = t.postal_code || '';
        document.getElementById('editTrainerSkills').value = t.skills || '';
        document.getElementById('editTrainerQuals').value = t.qualifications || '';
        document.getElementById('editTrainerCerts').value = t.certifications || '';
        document.getElementById('editTrainerLangs').value = t.languages || '';
        document.getElementById('editTrainerNotes').value = t.notes || '';
        new bootstrap.Modal(document.getElementById('editTrainerModal')).show();
    });
}

function saveTrainerEdit() {
    const form = document.getElementById('editTrainerForm');
    const data = Object.fromEntries(new FormData(form));
    const id = document.getElementById('editTrainerId').value;
    
    fetch(`/api/trainers/${id}`, {
        method: 'PUT',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
        body: JSON.stringify(data)
    }).then(r => r.json()).then(() => {
        alert('Updated!');
        bootstrap.Modal.getInstance(document.getElementById('editTrainerModal')).hide();
        location.reload();
    });
}
</script>
