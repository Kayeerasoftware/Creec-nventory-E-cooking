<!-- Edit Technician Modal -->
<div class="modal fade" id="editTechnicianModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Edit Technician Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <form id="editTechnicianForm">
                    @csrf
                    <input type="hidden" id="editTechId">
                    <div class="row g-3">
                        <div class="col-md-6"><label>Phone 1</label><input type="tel" class="form-control" name="phone_1" id="editPhone1"></div>
                        <div class="col-md-6"><label>Phone 2</label><input type="tel" class="form-control" name="phone_2" id="editPhone2"></div>
                        <div class="col-md-6"><label>WhatsApp</label><input type="tel" class="form-control" name="whatsapp" id="editWhatsapp"></div>
                        <div class="col-md-6"><label>Emergency Contact</label><input type="text" class="form-control" name="emergency_contact" id="editEmergency"></div>
                        <div class="col-md-6"><label>Emergency Phone</label><input type="tel" class="form-control" name="emergency_phone" id="editEmergencyPhone"></div>
                        <div class="col-md-6"><label>Village</label><input type="text" class="form-control" name="village" id="editVillage"></div>
                        <div class="col-md-6"><label>Postal Code</label><input type="text" class="form-control" name="postal_code" id="editPostal"></div>
                        <div class="col-12"><label>Skills</label><textarea class="form-control" name="skills" id="editSkills" rows="2"></textarea></div>
                        <div class="col-12"><label>Certifications</label><textarea class="form-control" name="certifications" id="editCerts" rows="2"></textarea></div>
                        <div class="col-12"><label>Training</label><textarea class="form-control" name="training" id="editTraining" rows="2"></textarea></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveTechEdit()">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
function openEditTech(id) {
    fetch(`/api/technicians/${id}`)
        .then(r => r.json())
        .then(t => {
            console.log('All fetched data:', t);
            document.getElementById('editTechId').value = t.id;
            document.getElementById('editPhone1').value = t.phone_1 || t.phone || '';
            document.getElementById('editPhone2').value = t.phone_2 || '';
            document.getElementById('editWhatsapp').value = t.whatsapp || '';
            document.getElementById('editEmergency').value = t.emergency_contact || '';
            document.getElementById('editEmergencyPhone').value = t.emergency_phone || '';
            document.getElementById('editVillage').value = t.village || '';
            document.getElementById('editPostal').value = t.postal_code || '';
            document.getElementById('editSkills').value = Array.isArray(t.skills) ? t.skills.join(', ') : (t.skills || '');
            document.getElementById('editCerts').value = Array.isArray(t.certifications) ? t.certifications.join(', ') : (t.certifications || '');
            document.getElementById('editTraining').value = t.training || '';
            new bootstrap.Modal(document.getElementById('editTechnicianModal')).show();
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Failed to load technician data');
        });
}

function saveTechEdit() {
    const data = Object.fromEntries(new FormData(document.getElementById('editTechnicianForm')));
    const id = document.getElementById('editTechId').value;
    
    fetch(`/api/technicians/${id}`, {
        method: 'PUT',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
        body: JSON.stringify(data)
    })
    .then(r => {
        if (!r.ok) {
            return r.json().then(err => { throw err; });
        }
        return r.json();
    })
    .then(res => {
        if(res.success) {
            alert('✓ ' + res.message);
            bootstrap.Modal.getInstance(document.getElementById('editTechnicianModal')).hide();
            location.reload();
        } else {
            alert('✗ Update failed: ' + (res.message || 'Unknown error'));
        }
    })
    .catch(err => {
        console.error('Full error:', err);
        const msg = err.message || (err.errors ? JSON.stringify(err.errors) : 'Unknown error');
        alert('✗ Update failed: ' + msg);
    });
}
</script>
