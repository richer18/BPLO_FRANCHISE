// src/BploForm.js
import React, { useState, useEffect } from "react";
import {
  Grid,
  TextField,
  MenuItem,
  Button,
  Paper,
  Typography,
  Box,
} from "@mui/material";
import axiosInstance from "../../api/axiosInstance";
import dayjs from "dayjs";

export default function BploForm() {
  const [records, setRecords] = useState([]);
  const [form, setForm] = useState({
    DATE: "", // date
    TRANSACTION_CODE: "", // backend will generate if empty
    FNAME: "",
    MNAME: "",
    LNAME: "",
    EXTNAME: "",
    GENDER: "",
    STREET: "",
    BARANGAY: "",
    MUNICIPALITY: "Zamboanguita",
    PROVINCE: "Negros Oriental",
    CELLPHONE: "",
    MCH_NO: "",
    FRANCHISE_NO: "",
    MAKE: "",
    MOTOR_NO: "",
    CHASSIS_NO: "",
    PLATE: "",
    COLOR: "",
    LTO_ORIGINAL_RECEIPT: "",
    LTO_CERTIFICATE_REGISTRATION: "",
    LTO_MV_FILE_NO: "",
    ORIGINAL_RECEIPT_PAYMENT: "",
    PAYMENT_DATE: "",
    AMOUNT: "",
    RENEW_FROM: "",
    RENEW_TO: "",
    MAYORS_PERMIT_NO: "",
    LICENSE_NO: "",
    LICENSE_VALID_DATE: "",
    DRIVER: "",
    STATUS: "",
    COMMENT: "",
  });

  const barangays = [
    "Mayabon",
    "Poblacion",
    "Malongcay Diot",
    "Calango",
    "Nabago",
    "Nasig-id",
    "Tampi",
    "Basak",
    "Cansalo-ay",
    "Lutoban",
  ];

  const makes = [
    "HONDA",
    "YAMAHA",
    "SUZUKI",
    "KAWASAKI",
    "SYM",
    "KYMCO",
    "OTHER",
  ];

  useEffect(() => {
    loadRecords();
  }, []);

  const loadRecords = async () => {
    try {
      const res = await axiosInstance.get("/bplo");
      setRecords(res.data);
    } catch (err) {
      console.error("Failed to load records", err);
    }
  };

  // compute renew_to and status whenever RENEW_FROM changes
  useEffect(() => {
  if (form.RENEW_FROM) {
    const renewFrom = dayjs(form.RENEW_FROM);
    const renewTo = renewFrom.add(1, "year").format("YYYY-MM-DD");
    const isActive = dayjs().isBefore(dayjs(renewTo)) || dayjs().isSame(dayjs(renewTo));

    setForm((prev) => ({
      ...prev,
      RENEW_TO: renewTo,
      STATUS: isActive ? "Pending" : "Completed", // ✅ Matches ENUM values
    }));
  } else {
    setForm((prev) => ({ ...prev, RENEW_TO: "", STATUS: "Pending" }));
  }
  // eslint-disable-next-line react-hooks/exhaustive-deps
}, [form.RENEW_FROM]);


  const handleChange = (e) => {
    const { name, value } = e.target;
    setForm((f) => ({ ...f, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    // If DATE not provided, set it to today
    const payload = { ...form };
    if (!payload.DATE) payload.DATE = dayjs().format("YYYY-MM-DD");

    try {
      await axiosInstance.post("/bplo", payload);
      alert("✅ Record saved successfully!");
      resetForm();
      loadRecords();
    } catch (err) {
      console.error("Save failed", err);
      alert("❌ Failed to save record. Check console.");
    }
  };

  const resetForm = () =>
    setForm({
      DATE: "",
      TRANSACTION_CODE: "",
      FNAME: "",
      MNAME: "",
      LNAME: "",
      EXTNAME: "",
      GENDER: "",
      STREET: "",
      BARANGAY: "",
      MUNICIPALITY: "Zamboanguita",
      PROVINCE: "Negros Oriental",
      CELLPHONE: "",
      MCH_NO: "",
      FRANCHISE_NO: "",
      MAKE: "",
      MOTOR_NO: "",
      CHASSIS_NO: "",
      PLATE: "",
      COLOR: "",
      LTO_ORIGINAL_RECEIPT: "",
      LTO_CERTIFICATE_REGISTRATION: "",
      LTO_MV_FILE_NO: "",
      ORIGINAL_RECEIPT_PAYMENT: "",
      PAYMENT_DATE: "",
      AMOUNT: "",
      RENEW_FROM: "",
      RENEW_TO: "",
      MAYORS_PERMIT_NO: "",
      LICENSE_NO: "",
      LICENSE_VALID_DATE: "",
      DRIVER: "",
      STATUS: "",
      COMMENT: "",
    });

  return (
    <Box sx={{ p: 4 }}>
      <Paper elevation={3} sx={{ p: 4, maxWidth: 1100, margin: "auto" }}>
        <Typography variant="h5" gutterBottom>
          📝 BPLO Franchise Record
        </Typography>

        <form onSubmit={handleSubmit}>
          <Grid container spacing={2}>
            {/* Row 1: DATE, TRANSACTION CODE (read-only) */}
            <Grid item xs={12} sm={4}>
              <TextField
                fullWidth
                type="date"
                label="Date"
                name="DATE"
                InputLabelProps={{ shrink: true }}
                value={form.DATE}
                onChange={handleChange}
              />
            </Grid>
            <Grid item xs={12} sm={8}>
              <TextField
                fullWidth
                label="Transaction Code (auto)"
                name="TRANSACTION_CODE"
                value={form.TRANSACTION_CODE}
                onChange={handleChange}
                helperText="Leave blank to auto-generate"
              />
            </Grid>

            {/* Name group */}
            <Grid item xs={12} sm={3}>
              <TextField label="First Name" name="FNAME" value={form.FNAME} onChange={handleChange} fullWidth />
            </Grid>
            <Grid item xs={12} sm={3}>
              <TextField label="Middle Name" name="MNAME" value={form.MNAME} onChange={handleChange} fullWidth />
            </Grid>
            <Grid item xs={12} sm={3}>
              <TextField label="Last Name" name="LNAME" value={form.LNAME} onChange={handleChange} fullWidth />
            </Grid>
            <Grid item xs={12} sm={3}>
              <TextField label="Extension" name="EXTNAME" value={form.EXTNAME} onChange={handleChange} fullWidth />
            </Grid>

            {/* Contact & address */}
            <Grid item xs={12} sm={3}>
              <TextField select fullWidth label="Gender" name="GENDER" value={form.GENDER} onChange={handleChange}>
                <MenuItem value="">Select</MenuItem>
                <MenuItem value="MALE">Male</MenuItem>
                <MenuItem value="FEMALE">Female</MenuItem>
              </TextField>
            </Grid>
            <Grid item xs={12} sm={5}>
              <TextField fullWidth label="Street" name="STREET" value={form.STREET} onChange={handleChange} />
            </Grid>
            <Grid item xs={12} sm={4}>
              <TextField select fullWidth label="Barangay" name="BARANGAY" value={form.BARANGAY} onChange={handleChange}>
                <MenuItem value="">Select Barangay</MenuItem>
                {barangays.map((b) => (
                  <MenuItem key={b} value={b}>
                    {b}
                  </MenuItem>
                ))}
              </TextField>
            </Grid>

            <Grid item xs={12} sm={4}>
              <TextField fullWidth label="Municipality" name="MUNICIPALITY" value={form.MUNICIPALITY} onChange={handleChange} />
            </Grid>
            <Grid item xs={12} sm={4}>
              <TextField fullWidth label="Province" name="PROVINCE" value={form.PROVINCE} onChange={handleChange} />
            </Grid>
            <Grid item xs={12} sm={4}>
              <TextField fullWidth label="Cellphone" name="CELLPHONE" value={form.CELLPHONE} onChange={handleChange} />
            </Grid>

            {/* Vehicle details (multiple fields) */}
            <Grid item xs={12} sm={3}>
              <TextField fullWidth label="MCH No" name="MCH_NO" value={form.MCH_NO} onChange={handleChange} />
            </Grid>
            <Grid item xs={12} sm={3}>
              <TextField fullWidth label="Franchise No" name="FRANCHISE_NO" value={form.FRANCHISE_NO} onChange={handleChange} />
            </Grid>
            <Grid item xs={12} sm={3}>
              <TextField select fullWidth label="Make" name="MAKE" value={form.MAKE} onChange={handleChange}>
                <MenuItem value="">Select</MenuItem>
                {makes.map((m) => (
                  <MenuItem key={m} value={m}>
                    {m}
                  </MenuItem>
                ))}
              </TextField>
            </Grid>
            <Grid item xs={12} sm={3}>
              <TextField fullWidth label="Motor No" name="MOTOR_NO" value={form.MOTOR_NO} onChange={handleChange} />
            </Grid>

            <Grid item xs={12} sm={3}>
              <TextField fullWidth label="Chassis No" name="CHASSIS_NO" value={form.CHASSIS_NO} onChange={handleChange} />
            </Grid>
            <Grid item xs={12} sm={3}>
              <TextField fullWidth label="Plate" name="PLATE" value={form.PLATE} onChange={handleChange} />
            </Grid>
            <Grid item xs={12} sm={3}>
              <TextField fullWidth label="Color" name="COLOR" value={form.COLOR} onChange={handleChange} />
            </Grid>
            <Grid item xs={12} sm={3}>
              <TextField fullWidth label="Driver" name="DRIVER" value={form.DRIVER} onChange={handleChange} />
            </Grid>

            {/* LTO fields */}
            <Grid item xs={12} sm={4}>
              <TextField fullWidth label="LTO Original Receipt" name="LTO_ORIGINAL_RECEIPT" value={form.LTO_ORIGINAL_RECEIPT} onChange={handleChange} />
            </Grid>
            <Grid item xs={12} sm={4}>
              <TextField fullWidth label="LTO Certificate Registration" name="LTO_CERTIFICATE_REGISTRATION" value={form.LTO_CERTIFICATE_REGISTRATION} onChange={handleChange} />
            </Grid>
            <Grid item xs={12} sm={4}>
              <TextField fullWidth label="LTO MV File No" name="LTO_MV_FILE_NO" value={form.LTO_MV_FILE_NO} onChange={handleChange} />
            </Grid>

            <Grid item xs={12} sm={4}>
              <TextField fullWidth label="Original Receipt Payment" name="ORIGINAL_RECEIPT_PAYMENT" value={form.ORIGINAL_RECEIPT_PAYMENT} onChange={handleChange} />
            </Grid>
            <Grid item xs={12} sm={4}>
              <TextField fullWidth type="date" label="Payment Date" name="PAYMENT_DATE" InputLabelProps={{ shrink: true }} value={form.PAYMENT_DATE} onChange={handleChange} />
            </Grid>
            <Grid item xs={12} sm={4}>
              <TextField fullWidth label="Amount" name="AMOUNT" value={form.AMOUNT} onChange={handleChange} />
            </Grid>

            {/* Renewal and license */}
            <Grid item xs={12} sm={4}>
              <TextField fullWidth type="date" label="Renew From" name="RENEW_FROM" InputLabelProps={{ shrink: true }} value={form.RENEW_FROM} onChange={handleChange} />
            </Grid>
            <Grid item xs={12} sm={4}>
              <TextField fullWidth type="date" label="Renew To (auto)" name="RENEW_TO" InputLabelProps={{ shrink: true }} value={form.RENEW_TO} onChange={handleChange} disabled />
            </Grid>
            <Grid item xs={12} sm={4}>
              <TextField fullWidth label="Status" name="STATUS" value={form.STATUS} onChange={handleChange} disabled />
            </Grid>

            <Grid item xs={12} sm={4}>
              <TextField fullWidth label="Mayor's Permit No" name="MAYORS_PERMIT_NO" value={form.MAYORS_PERMIT_NO} onChange={handleChange} />
            </Grid>
            <Grid item xs={12} sm={4}>
              <TextField fullWidth label="License No" name="LICENSE_NO" value={form.LICENSE_NO} onChange={handleChange} />
            </Grid>
            <Grid item xs={12} sm={4}>
              <TextField fullWidth type="date" label="License Valid Date" name="LICENSE_VALID_DATE" InputLabelProps={{ shrink: true }} value={form.LICENSE_VALID_DATE} onChange={handleChange} />
            </Grid>

            {/* Comment */}
            <Grid item xs={12}>
              <TextField fullWidth multiline rows={3} label="Comment" name="COMMENT" value={form.COMMENT} onChange={handleChange} />
            </Grid>

            {/* Submit */}
            <Grid item xs={12}>
              <Button type="submit" variant="contained" color="primary" fullWidth>
                💾 Save Record
              </Button>
            </Grid>
          </Grid>
        </form>
      </Paper>

      {/* Existing records */}
      <Paper elevation={2} sx={{ mt: 4, p: 2 }}>
        <Typography variant="h6">📋 Existing Records</Typography>
        <table border="1" cellPadding="8" style={{ width: "100%", borderCollapse: "collapse", marginTop: "10px" }}>
          <thead style={{ backgroundColor: "#1976d2", color: "white" }}>
            <tr>
              <th>ID</th>
              <th>TRANSACTION CODE</th>
              <th>NAME</th>
              <th>BARANGAY</th>
              <th>MAKE</th>
              <th>RENEW FROM</th>
              <th>RENEW TO</th>
              <th>STATUS</th>
            </tr>
          </thead>
          <tbody>
            {records.map((r) => (
              <tr key={r.ID}>
                <td>{r.ID}</td>
                <td>{r.TRANSACTION_CODE}</td>
                <td>{r.FNAME} {r.MNAME} {r.LNAME}</td>
                <td>{r.BARANGAY}</td>
                <td>{r.MAKE}</td>
                <td>{r.RENEW_FROM}</td>
                <td>{r.RENEW_TO}</td>
                <td>{r.STATUS}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </Paper>
    </Box>
  );
}
