import React, { useEffect, useState } from "react";
import axiosInstance from "../../api/axiosInstance";
import { Close as CloseIcon } from "@mui/icons-material";
import {
  Autocomplete,
  Box,
  Button,
  Card,
  Chip,
  Dialog,
  DialogContent,
  DialogTitle,
  InputAdornment,
  IconButton,
  Menu,
  MenuItem,
  Paper,
  styled,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  TextField,
  Tooltip,
  Typography,
} from "@mui/material";

import TablePagination from "@mui/material/TablePagination";
import AccountBalanceIcon from "@mui/icons-material/AccountBalance";
import BusinessCenterIcon from "@mui/icons-material/BusinessCenter";
import GavelIcon from "@mui/icons-material/Gavel";
import ReceiptLongIcon from "@mui/icons-material/ReceiptLong";
import StorefrontIcon from "@mui/icons-material/Storefront";
import BploForm from "../form/BploForm";

const StyledTableCell = styled(TableCell)(({ theme }) => ({
  whiteSpace: "nowrap",
  fontWeight: "bold",
  textAlign: "center",
  background: "linear-gradient(135deg, #1976d2, #63a4ff)",
  color: theme.palette.common.white,
  borderBottom: `2px solid ${theme.palette.primary.dark}`,
  fontSize: 14,
}));

const formatDate = (dateString) => {
  const date = new Date(dateString);
  const options = { month: "short", day: "numeric", year: "numeric" };
  return date.toLocaleDateString("en-US", options);
};

function FrontPage() {
  const [records, setRecords] = useState([]);
  const [page, setPage] = useState(0);
  const [rowsPerPage, setRowsPerPage] = useState(10);

  // Dialog state
  const [openForm, setOpenForm] = useState(false);
  const handleOpenForm = () => setOpenForm(true);
  const handleCloseForm = () => setOpenForm(false);

  // Menu state for print
  const [anchorEl, setAnchorEl] = useState(null);
  const [selectedRecord, setSelectedRecord] = useState(null);

  useEffect(() => {
    fetchRecords();
  }, []);

  const fetchRecords = async () => {
    try {
      const response = await axiosInstance.get("/bplo");
      setRecords(response.data);
    } catch (error) {
      console.error("Error fetching records:", error);
    }
  };

  const handleChangePage = (event, newPage) => setPage(newPage);
  const handleChangeRowsPerPage = (event) => {
    setRowsPerPage(parseInt(event.target.value, 10));
    setPage(0);
  };

  // Menu handlers
  const handleMenuOpen = (event, record) => {
    setAnchorEl(event.currentTarget);
    setSelectedRecord(record);
  };
  const handleMenuClose = () => {
    setAnchorEl(null);
    setSelectedRecord(null);
  };

  const handlePrint = (type) => {
    handleMenuClose();
    if (!selectedRecord) return console.warn("No record selected for printing");

    const base = "http://localhost/BPLO_FRANCHISE/php_scripts";
    let url = "";

    switch (type) {
      case "application":
        url = `${base}/fill_pdf_application.php?id=${selectedRecord.ID}`;
        break;
      case "certification":
        url = `${base}/fill_pdf_certification.php?id=${selectedRecord.ID}`;
        break;
      case "order":
        url = `${base}/fill_pdf_order.php?id=${selectedRecord.ID}`;
        break;
      case "pnp":
        url = `${base}/fill_pdf_pnp_motor_vehicle_clearance_certification.php?id=${selectedRecord.ID}`;
        break;
      default:
        return;
    }

    window.open(url, "_blank");
  };

  return (
    <Box
      sx={{
        flexGrow: 1,
        padding: 3,
        minHeight: "100vh",
      }}
    >
      <Box sx={{ mb: 4 }}>
        {/* Search & Filters Row */}
        <Box display="flex" alignItems="center" gap={3} sx={{ py: 2 }}>
          <Box display="flex" alignItems="center" gap={2} flexGrow={1}>
            <TextField
              fullWidth
              variant="outlined"
              label="Search Records"
              placeholder="Name or Receipt Number"
              InputProps={{
                startAdornment: (
                  <InputAdornment position="start">{/* icon here */}</InputAdornment>
                ),
                sx: { borderRadius: "8px" },
              }}
            />
            <Box display="flex" gap={2}>
              <Autocomplete
                disablePortal
                sx={{ width: 180 }}
                renderInput={(params) => (
                  <TextField {...params} label="Select Month" variant="outlined" />
                )}
              />

              <Autocomplete
                disablePortal
                sx={{ width: 150 }}
                renderInput={(params) => (
                  <TextField {...params} label="Select Year" variant="outlined" />
                )}
              />

              <Button
                variant="contained"
                color="primary"
                sx={{
                  px: 4,
                  height: "56px",
                  color: "white",
                  borderRadius: "8px",
                  boxShadow: "none",
                  "&:hover": { boxShadow: "0px 3px 6px rgba(0, 0, 0, 0.1)" },
                }}
              >
                Apply Filters
              </Button>
            </Box>
          </Box>
        </Box>

        {/* Action Buttons Row */}
        <Box display="flex" alignItems="center" gap={2} sx={{ py: 1 }}>
          <Box display="flex" gap={2} flexGrow={1}>
            <Tooltip title="Add New Entry" arrow>
              <Button
                variant="contained"
                sx={{
                  px: 3.5,
                  backgroundColor: "#1976d2",
                  color: "white",
                  "&:hover": {
                    backgroundColor: "#1565c0",
                    transform: "translateY(-1px)",
                  },
                  textTransform: "none",
                  fontSize: 15,
                  fontWeight: 600,
                  borderRadius: "10px",
                  minWidth: "130px",
                  height: "44px",
                }}
                onClick={handleOpenForm} // ✅ fixed
              >
                New Entry
              </Button>
            </Tooltip>

            <Tooltip title="Generate Daily Report" arrow>
              <Button
                variant="contained"
                color="success"
                sx={{
                  px: 3.5,
                  backgroundColor: "#2e7d32",
                  color: "white",
                  "&:hover": { backgroundColor: "#1b5e20" },
                }}
              >
                Renew
              </Button>
            </Tooltip>

            <Tooltip title="Generate Receipt Report" arrow>
              <Button
                variant="contained"
                color="secondary"
                sx={{
                  px: 3.5,
                  backgroundColor: "#7b1fa2",
                  color: "white",
                  "&:hover": { backgroundColor: "#6a1b9a" },
                }}
              >
                Drop
              </Button>
            </Tooltip>
          </Box>

          <Box display="flex" gap={2}>
            <Tooltip title="Financial Reports" arrow>
              <Button variant="contained" color="error">
                Financial Report
              </Button>
            </Tooltip>
            <Tooltip title="Export Data" arrow>
              <Button variant="contained" color="info">
                Download
              </Button>
            </Tooltip>
          </Box>
        </Box>

        {/* Summary Cards */}
        <Box
          display="flex"
          justifyContent="space-between"
          gap={3}
          sx={{ mt: 4, flexDirection: { xs: "column", sm: "row" } }}
        >
          {[
            {
              text: "Total Revenue",
              icon: <AccountBalanceIcon />,
              gradient: "linear-gradient(135deg, #1976d2, #63a4ff)",
            },
            {
              text: "Total Registered",
              icon: <BusinessCenterIcon />,
              gradient: "linear-gradient(135deg, #2e7d32, #66bb6a)",
            },
            {
              text: "Total Renew",
              icon: <GavelIcon />,
              gradient: "linear-gradient(135deg, #ed6c02, #ffb74d)",
            },
            {
              text: "Total Expiry",
              icon: <StorefrontIcon />,
              gradient: "linear-gradient(135deg, #6a1b9a, #ab47bc)",
            },
            {
              text: "Total Expired",
              icon: <ReceiptLongIcon />,
              gradient: "linear-gradient(135deg, #00838f, #4dd0e1)",
            },
          ].map(({ text, icon, gradient }) => (
            <Card
              key={text}
              sx={{
                flex: 1,
                p: 3,
                borderRadius: "16px",
                background: gradient,
                color: "white",
                boxShadow: "0 8px 32px rgba(0,0,0,0.12)",
              }}
            >
              <Box display="flex" justifyContent="space-between" alignItems="flex-start">
                <Box>
                  <Typography variant="subtitle2">{text}</Typography>
                  <Typography variant="h5">₱0.00</Typography>
                </Box>
                <Box sx={{ opacity: 0.2 }}>{icon}</Box>
              </Box>
            </Card>
          ))}
        </Box>
      </Box>

      {/* TABLE */}
      <TableContainer component={Paper} sx={{ borderRadius: 4, boxShadow: 6, mt: 4 }}>
        <Table stickyHeader>
          <TableHead>
            <TableRow>
              <StyledTableCell>TRANSACTION CODE</StyledTableCell>
              <StyledTableCell>NAME</StyledTableCell>
              <StyledTableCell>BARANGAY</StyledTableCell>
              <StyledTableCell>MAKE</StyledTableCell>
              <StyledTableCell>MCH NO</StyledTableCell>
              <StyledTableCell>CASE NO</StyledTableCell>
              <StyledTableCell>RENEW FROM</StyledTableCell>
              <StyledTableCell>RENEW TO</StyledTableCell>
              <StyledTableCell>STATUS</StyledTableCell>
              <StyledTableCell>ACTION</StyledTableCell>
            </TableRow>
          </TableHead>

          <TableBody>
            {records
              .slice(page * rowsPerPage, page * rowsPerPage + rowsPerPage)
              .map((r) => (
                <TableRow key={r.ID} hover>
                  <TableCell align="center">{r.TRANSACTION_CODE}</TableCell>
                  <TableCell align="center">
                    {r.FNAME} {r.LNAME}
                  </TableCell>
                  <TableCell align="center">{r.BARANGAY}</TableCell>
                  <TableCell align="center">{r.MAKE}</TableCell>
                  <TableCell align="center">{r.MCH_NO}</TableCell>
                  <TableCell align="center">{r.FRANCHISE_NO}</TableCell>
                  <TableCell align="center">{formatDate(r.RENEW_FROM)}</TableCell>
                  <TableCell align="center">{formatDate(r.RENEW_TO)}</TableCell>
                  <TableCell align="center">
                    <Chip label={r.STATUS || "Pending"} color="info" size="small" />
                  </TableCell>
                  <TableCell align="center">
                    <Button
                      variant="contained"
                      color="success"
                      size="small"
                      onClick={(e) => handleMenuOpen(e, r)}
                    >
                      🖨️ Print
                    </Button>
                  </TableCell>
                </TableRow>
              ))}
          </TableBody>
        </Table>

        <TablePagination
          rowsPerPageOptions={[10, 20, 50]}
          component="div"
          count={records.length}
          rowsPerPage={rowsPerPage}
          page={page}
          onPageChange={handleChangePage}
          onRowsPerPageChange={handleChangeRowsPerPage}
        />
      </TableContainer>

      {/* PRINT MENU */}
      <Menu
        anchorEl={anchorEl}
        open={Boolean(anchorEl)}
        onClose={handleMenuClose}
        anchorOrigin={{ vertical: "bottom", horizontal: "left" }}
        transformOrigin={{ vertical: "top", horizontal: "left" }}
      >
        <MenuItem onClick={() => handlePrint("application")}>Application PDF</MenuItem>
        <MenuItem onClick={() => handlePrint("certification")}>Certification PDF</MenuItem>
        <MenuItem onClick={() => handlePrint("order")}>Order PDF</MenuItem>
        <MenuItem onClick={() => handlePrint("pnp")}>PNP Clearance PDF</MenuItem>
      </Menu>

      {/* DIALOG FORM */}
      <Dialog open={openForm} onClose={handleCloseForm} maxWidth="md" fullWidth>
        <DialogTitle sx={{ fontWeight: "bold" }}>
          📝 New BPLO Franchise Entry
          <IconButton
            aria-label="close"
            onClick={handleCloseForm}
            sx={{ position: "absolute", right: 8, top: 8 }}
          >
            <CloseIcon />
          </IconButton>
        </DialogTitle>

        <DialogContent dividers>
          <BploForm onClose={handleCloseForm} />
        </DialogContent>
      </Dialog>
    </Box>
  );
}

export default FrontPage;
