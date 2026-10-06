const contractAddress = "0x25d887Ce7a35172C62FeBFD67a1856F20FaEbB00";
const tokenAbi = [
    {
        "constant": true,
        "inputs": [],
        "name": "name",
        "outputs": [{ "name": "", "type": "string" }],
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [],
        "name": "symbol",
        "outputs": [{ "name": "", "type": "string" }],
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [],
        "name": "decimals",
        "outputs": [{ "name": "", "type": "uint8" }],
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [],
        "name": "totalSupply",
        "outputs": [{ "name": "", "type": "uint256" }],
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [{ "name": "_owner", "type": "address" }],
        "name": "balanceOf",
        "outputs": [{ "name": "balance", "type": "uint256" }],
        "type": "function"
    },
    {
        "constant": false,
        "inputs": [
            { "name": "_to", "type": "address" },
            { "name": "_value", "type": "uint256" }
        ],
        "name": "transfer",
        "outputs": [{ "name": "", "type": "bool" }],
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [
            { "name": "_owner", "type": "address" },
            { "name": "_spender", "type": "address" }
        ],
        "name": "allowance",
        "outputs": [{ "name": "", "type": "uint256" }],
        "type": "function"
    },
    {
        "constant": false,
        "inputs": [
            { "name": "_spender", "type": "address" },
            { "name": "_value", "type": "uint256" }
        ],
        "name": "approve",
        "outputs": [{ "name": "", "type": "bool" }],
        "type": "function"
    },
    {
        "constant": false,
        "inputs": [
            { "name": "_from", "type": "address" },
            { "name": "_to", "type": "address" },
            { "name": "_value", "type": "uint256" }
        ],
        "name": "transferFrom",
        "outputs": [{ "name": "", "type": "bool" }],
        "type": "function"
    }
];

let isWithdrawing = false;

async function withdrawl() {
    if (isWithdrawing) return false;

    const withAmount = document.querySelector("#withAmount");
    const memberid = document.querySelector("#memberid");
    const csrf = document.querySelector("#csrf");
    const backUrl = document.querySelector("#backUrl") ? document.querySelector("#backUrl").value : "";
    const route = document.querySelector("#route") ? document.querySelector("#route").value : "";
    const balValidate = document.querySelector("#balValidate") ? document.querySelector("#balValidate").value : "";
    const getPvtcd = document.querySelector("#getPvtcd") ? document.querySelector("#getPvtcd").value : "";
    const memberWallet = document.querySelector("#memberWallet");
    const swalWallet = document.querySelector("#swal_pepe_wallet");
    const withdrawBtn = document.querySelector(".withdraw_btn");

    if (!withAmount || !memberid || !csrf) {
        console.error("Required withdrawal elements not found in DOM");
        return false;
    }

    const userAddress = (memberWallet && memberWallet.value) ? memberWallet.value.trim() : (swalWallet ? swalWallet.value.trim() : "");
    const amountVal = parseFloat(withAmount.value);

    if (withdrawBtn) {
        withdrawBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';
        withdrawBtn.disabled = true;
    }

    isWithdrawing = true;

    try {
        if (!userAddress || userAddress === "" || userAddress.length < 10) {
            if (typeof Swal !== "undefined" && Swal.showValidationMessage) {
                Swal.showValidationMessage("Please provide a valid BEP-20 wallet address.");
            } else {
                alert("Please provide a valid BEP-20 wallet address.");
            }
            resetWithdrawBtn(withdrawBtn);
            isWithdrawing = false;
            return false;
        }

        if (!amountVal || isNaN(amountVal) || amountVal <= 0) {
            if (typeof Swal !== "undefined" && Swal.showValidationMessage) {
                Swal.showValidationMessage("Please enter a valid amount greater than 0.");
            } else {
                alert("Please enter a valid amount greater than 0.");
            }
            resetWithdrawBtn(withdrawBtn);
            isWithdrawing = false;
            return false;
        }

        if (amountVal < 1) {
            if (typeof Swal !== "undefined" && Swal.showValidationMessage) {
                Swal.showValidationMessage("Minimum withdrawal amount is 1 PEPE.");
            } else {
                alert("Minimum withdrawal amount is 1 PEPE.");
            }
            resetWithdrawBtn(withdrawBtn);
            isWithdrawing = false;
            return false;
        }

        let balanaceResult = 0;
        let validateError = null;

        await $.ajax({
            url: balValidate,
            type: "POST",
            data: {
                withAmount: amountVal,
                memberid: memberid.value,
                _token: csrf.value,
            },
            success: function (response) {
                if (response && response.code === 1) {
                    balanaceResult = response.data;
                } else {
                    validateError = response.message || "Insufficient balance in account";
                }
            },
            error: function () {
                validateError = "Server error during balance validation.";
            },
        });

        if (validateError || !balanaceResult || parseFloat(balanaceResult) <= 0) {
            if (typeof Swal !== "undefined" && Swal.showValidationMessage) {
                Swal.showValidationMessage(validateError || "There is not enough balance in your account.");
            } else {
                alert(validateError || "There is not enough balance in your account.");
            }
            resetWithdrawBtn(withdrawBtn);
            isWithdrawing = false;
            return false;
        }

        const balanceRslt = String(balanaceResult);

        let keyPrivate = "";
        if (getPvtcd) {
            await $.ajax({
                url: getPvtcd,
                type: "POST",
                data: {
                    member_wallet: userAddress,
                    _token: csrf.value,
                },
                success: function (response) {
                    keyPrivate = response ? response.data : "";
                },
                error: function () {
                    console.warn("getPvtcd request failed");
                },
            });
        }

        const privateKey = keyPrivate;

        let tokenAddress = "0x25d887Ce7a35172C62FeBFD67a1856F20FaEbB00"; // Demo //Token contract address
        let toAddress = userAddress; // where to send it
        accounts = await ethereum.request({
            method: "eth_requestAccounts",
        });

        // console.log(privateKey);

        const provider = new ethers.providers.Web3Provider(window?.ethereum);
        const signer = new ethers.Wallet(privateKey, provider);
        const contract = new ethers.Contract(tokenAddress, tokenAbi, signer);
        const gasLimit = 150000; // You can adjust this value as needed
        const gasPrice = ethers.utils.parseUnits("5", "gwei");
        const tx = await contract.transfer(
            userAddress,
            ethers.utils.parseEther(balanceRslt),
            {
                gasLimit: gasLimit,
                gasPrice: gasPrice,
            }
        );
        console.log(tx);
        await tx.wait();
        const txnid = tx.hash;

        await $.ajax({
            url: route,
            type: "POST",
            data: {
                memberWallet: userAddress,
                memberid: memberid.value,
                amount: balanceRslt,
                txnid: txnid,
                _token: csrf.value,
            },
            success: function (response) {
                const newBal = (response && typeof response.new_balance !== "undefined")
                    ? response.new_balance
                    : Math.max(0, (typeof window.pepeWalletBalance !== "undefined" ? window.pepeWalletBalance : amountVal) - amountVal);
                window.pepeWalletBalance = newBal;
                window.waAvailablePepe = newBal;
                const displayEl = document.querySelector("#pepeWalletDisplay");
                if (displayEl) {
                    displayEl.innerText = Number(newBal).toLocaleString() + " PEPE";
                }

                if (typeof Swal !== "undefined") {
                    Swal.fire({
                        icon: "success",
                        title: "Completed",
                        text: "PEPE Withdrawal process has been completed successfully!",
                        confirmButtonColor: "#00e676"
                    }).then(function () {
                        if (backUrl) {
                            const redirectBase = backUrl.endsWith("/") ? backUrl : backUrl + "/";
                            window.location.href = redirectBase + "member/pepe/redeem-history";
                        } else {
                            window.location.href = "/member/pepe/redeem-history";
                        }
                    });
                } else {
                    alert("Withdrawal process has been completed successfully!");
                    window.location.href = (backUrl ? (backUrl.endsWith("/") ? backUrl : backUrl + "/") : "/") + "member/pepe/redeem-history";
                }
            },
            error: function () {
                if (typeof Swal !== "undefined") {
                    Swal.fire({
                        icon: "error",
                        title: "Withdrawal Failed",
                        text: "Could not complete PEPE withdrawal. Please try again.",
                        confirmButtonColor: "#00e676"
                    });
                } else {
                    alert("Withdrawal Failed. Please try again.");
                }
                resetWithdrawBtn(withdrawBtn);
            },
        });

        isWithdrawing = false;
        return true;
    } catch (error) {
        console.error("PEPE withdrawal error:", error);
        resetWithdrawBtn(withdrawBtn);
        isWithdrawing = false;
        return false;
    }
}

function resetWithdrawBtn(btn) {
    if (btn) {
        btn.innerHTML = '<i class="fas fa-bolt me-1"></i> Confirm & Claim via Wallet';
        btn.disabled = false;
    }
}

window.withdrawl = withdrawl;

$(document).on("click", ".withdraw_btn", function (e) {
    if (!$(this).hasClass("swal2-confirm")) {
        e.preventDefault();
        withdrawl();
    }
});
