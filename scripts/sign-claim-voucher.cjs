/**
 * Cryptographic Voucher Signer for PEPE Claim Distributor
 * Uses Ethers.js to sign claim authorizations compatible with Solidity ECDSA.
 */
const { ethers } = require('ethers');

async function main() {
    let inputData = '';
    
    // Support Base64 encoded payload via command line argument (prevents Windows shell escaping issues)
    if (process.argv.length > 2) {
        const arg = process.argv[2];
        try {
            // Check if base64 encoded
            const decoded = Buffer.from(arg, 'base64').toString('utf-8');
            if (decoded.trim().startsWith('{')) {
                inputData = decoded;
            } else {
                inputData = arg;
            }
        } catch (e) {
            inputData = arg;
        }
    } else {
        try {
            inputData = require('fs').readFileSync(0, 'utf-8');
        } catch (e) {
            console.log(JSON.stringify({ success: false, error: 'Failed to read input from stdin' }));
            process.exit(1);
        }
    }

    try {
        const payload = JSON.parse(inputData);
        const { privateKey, recipient, amount, decimals = 18, nonce, deadline, contractAddress } = payload;

        if (!privateKey || !recipient || !amount || !nonce || !deadline || !contractAddress) {
            console.log(JSON.stringify({
                success: false,
                error: 'Missing required parameters (privateKey, recipient, amount, nonce, deadline, contractAddress)'
            }));
            process.exit(1);
        }

        const wallet = new ethers.Wallet(privateKey);
        
        // Format raw token amount
        let amountRaw;
        if (typeof amount === 'string' && amount.includes('.')) {
            amountRaw = ethers.utils.parseUnits(amount, decimals);
        } else {
            amountRaw = ethers.utils.parseUnits(amount.toString(), decimals);
        }

        // Pack: recipient (address), amount (uint256), nonce (uint256), deadline (uint256), contractAddress (address)
        const messageHash = ethers.utils.solidityKeccak256(
            ['address', 'uint256', 'uint256', 'uint256', 'address'],
            [
                ethers.utils.getAddress(recipient),
                amountRaw.toString(),
                nonce.toString(),
                parseInt(deadline),
                ethers.utils.getAddress(contractAddress)
            ]
        );

        // Sign with Ethereum Signed Message prefix
        const signature = await wallet.signMessage(ethers.utils.arrayify(messageHash));

        // Verify recovered signer matches
        const recovered = ethers.utils.verifyMessage(ethers.utils.arrayify(messageHash), signature);
        if (recovered.toLowerCase() !== wallet.address.toLowerCase()) {
            throw new Error('Signer verification failed internally');
        }

        console.log(JSON.stringify({
            success: true,
            signature: signature,
            signer: wallet.address,
            recipient: ethers.utils.getAddress(recipient),
            amountRaw: amountRaw.toString(),
            nonce: nonce.toString(),
            deadline: parseInt(deadline),
            contractAddress: ethers.utils.getAddress(contractAddress)
        }));
    } catch (err) {
        console.log(JSON.stringify({
            success: false,
            error: err.message || 'Signing failed'
        }));
        process.exit(1);
    }
}

main();
